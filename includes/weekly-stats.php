<?php
// Weekly results for case studies, entered week by week from the admin
// ("Weekly Stats" on each case study) and stored in case_study_weekly_stats.
// Each case study's existing stats are imported once as a starting entry
// (is_baseline = 1), so totals carry on from where they were and every new
// week is added on top. Read live on every page load: saving a week in the
// admin updates the case study page, the service pages and the homepage
// totals with no code change.

// Metric column => label shown on the site. Labels for the first four match
// the "Results At A Glance" labels, so weekly totals slot straight into the
// homepage combined stats.
const WEEKLY_METRICS = [
    'organic_clicks' => 'Organic Clicks',
    'search_impressions' => 'Search Impressions',
    'gbp_interactions' => 'GBP Interactions',
    'gbp_calls' => 'Calls From GBP',
    'gbp_directions' => 'Direction Requests',
    'gbp_website_clicks' => 'Website Clicks',
    'gbp_chat_clicks' => 'Chat Clicks',
    'shop_sales' => 'Total Sales',
    'shop_orders' => 'Orders',
    'shop_sessions' => 'Sessions',
];

// The metrics that also count toward the combined stats on the homepage and
// service pages. Shop figures stay on each case study's own page -- adding
// different stores' sales together would not mean much.
const WEEKLY_COMBINED_METRICS = ['organic_clicks', 'search_impressions', 'gbp_interactions', 'gbp_calls', 'gbp_directions', 'gbp_website_clicks', 'gbp_chat_clicks'];

function weekly_stats_create_table_sql(): string
{
    $metricCols = implode(",\n    ", array_map(fn($col) => "$col DECIMAL(14,2) NULL", array_keys(WEEKLY_METRICS)));
    return "CREATE TABLE IF NOT EXISTS case_study_weekly_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_study_id INT NOT NULL,
    is_baseline TINYINT(1) NOT NULL DEFAULT 0,
    label VARCHAR(100) NOT NULL DEFAULT '',
    week_start DATE NULL,
    week_end DATE NULL,
    $metricCols,
    gsc_ctr DECIMAL(6,2) NULL,
    gsc_position DECIMAL(6,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_case_study (case_study_id)
)";
}

// Reads a number typed in the admin ("312", "1,998", "7.2K", "A$247.9K",
// "0.94%") as a plain number; blank means "not tracked this week".
function parse_weekly_number(?string $v): ?float
{
    $v = trim((string) $v);
    $v = preg_replace('/[^0-9.,kKmM+-]/', '', $v);
    if ($v === '' || !preg_match('/\d/', $v)) {
        return null;
    }
    return parse_stat_number($v);
}

// Display format for a weekly value or total: exact with commas below 10,000
// ("1,998"), then K with one decimal ("247.9K"), then M ("1.25M").
function format_weekly_value(string $metric, float $v): string
{
    $abs = abs($v);
    if ($abs < 10000) {
        $text = number_format($v, $v == floor($v) ? 0 : 2);
    } elseif ($abs < 1000000) {
        $text = rtrim(rtrim(number_format($v / 1000, 1, '.', ''), '0'), '.') . 'K';
    } else {
        $text = rtrim(rtrim(number_format($v / 1000000, 2, '.', ''), '0'), '.') . 'M';
    }
    return $metric === 'shop_sales' ? 'A$' . $text : $text;
}

// Every case study's weekly rows, keyed by case_study_id, starting entry
// first and then weeks in date order. [] if the table can't be read (e.g.
// not set up yet), so pages simply fall back to the manual stats.
function fetch_all_weekly_stats(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    try {
        $rows = get_db()->query('SELECT * FROM case_study_weekly_stats ORDER BY case_study_id ASC, is_baseline DESC, week_start ASC, id ASC')->fetchAll();
    } catch (PDOException $e) {
        return $cache = [];
    }
    $byStudy = [];
    foreach ($rows as $row) {
        $byStudy[(int) $row['case_study_id']][] = $row;
    }
    return $cache = $byStudy;
}

function fetch_weekly_stats(int $caseStudyId): array
{
    return fetch_all_weekly_stats()[$caseStudyId] ?? [];
}

// Totals across every row, plus the latest week and the one before it (for
// the "vs previous week" change). null when there is nothing to show.
function summarise_weekly_stats(array $rows): ?array
{
    if (!$rows) {
        return null;
    }
    $totals = array_fill_keys(array_keys(WEEKLY_METRICS), null);
    // CTR over the total period: a week's own typed CTR is used when it has
    // one, otherwise clicks / impressions; weeks are weighted by their
    // impressions. Entries without impressions (common for the starting entry)
    // are left out rather than inflating the figure.
    $rateSums = ['ctr_x_impressions' => 0.0, 'impressions' => 0.0];
    $baseline = null;
    $weeks = [];
    foreach ($rows as $row) {
        $impressions = $row['search_impressions'] ?? null;
        if ($impressions !== null && $impressions !== '' && (float) $impressions > 0) {
            $rowCtr = weekly_row_ctr($row);
            if ($rowCtr !== null) {
                $rateSums['ctr_x_impressions'] += $rowCtr * (float) $impressions;
                $rateSums['impressions'] += (float) $impressions;
            }
        }
        foreach (WEEKLY_METRICS as $metric => $label) {
            $value = $row[$metric] ?? null;
            if ($value !== null && $value !== '') {
                $totals[$metric] = ($totals[$metric] ?? 0) + (float) $value;
            }
        }
        if ((int) $row['is_baseline'] === 1) {
            $baseline = $row;
        } else {
            $weeks[] = $row;
        }
    }
    if (count(array_filter($totals, fn($v) => $v !== null)) === 0) {
        return null;
    }
    $latest = $weeks ? $weeks[count($weeks) - 1] : null;
    $previous = count($weeks) > 1 ? $weeks[count($weeks) - 2] : null;

    $start = $baseline ? ($baseline['label'] !== '' ? $baseline['label'] : 'Before weekly tracking') : format_week_date($weeks[0]['week_start'] ?? null);
    $end = $latest ? format_week_date($latest['week_end'] ?: $latest['week_start']) : '';

    $totals['_ctr'] = $rateSums['impressions'] > 0 ? $rateSums['ctr_x_impressions'] / $rateSums['impressions'] : null;

    return [
        'totals' => $totals,
        'latest' => $latest,
        'previous' => $previous,
        'period' => $end === '' || $start === $end ? $start : ($baseline ? "$start, plus weekly updates to $end" : "$start – $end"),
        'latest_period' => $latest ? format_week_range($latest['week_start'], $latest['week_end']) : '',
    ];
}

function format_week_date(?string $date): string
{
    $ts = $date ? strtotime($date) : false;
    return $ts ? date('j M Y', $ts) : '';
}

function format_week_range(?string $start, ?string $end): string
{
    $s = $start ? strtotime($start) : false;
    $e = $end ? strtotime($end) : false;
    if (!$s) {
        return '';
    }
    if (!$e || $e === $s) {
        return date('j M Y', $s);
    }
    return date('M', $s) === date('M', $e) ? date('j', $s) . '–' . date('j M Y', $e) : date('j M', $s) . ' – ' . date('j M Y', $e);
}

// The stat boxes for one week (or the totals): [value, label, change] for
// every metric with a value, plus the click-through rate worked
// out from the underlying numbers rather than added up.
function weekly_stat_boxes(array $values, ?array $previous = null): array
{
    $boxes = [];
    foreach (WEEKLY_METRICS as $metric => $label) {
        $v = $values[$metric] ?? null;
        if ($v === null || $v === '') {
            continue;
        }
        $change = null;
        $prev = $previous[$metric] ?? null;
        if ($previous !== null && $prev !== null && $prev !== '' && (float) $prev != 0) {
            $change = ((float) $v - (float) $prev) / (float) $prev * 100;
        }
        $boxes[] = [format_weekly_value($metric, (float) $v), $label, $change];
    }
    // Totals carry their CTR precomputed (_ctr, see summarise_weekly_stats());
    // a single week's CTR comes straight from its own numbers. Conversion
    // rate is deliberately not worked out here: Shopify calculates it its own
    // way (converted sessions, not orders / sessions), so ours would not
    // match the figure clients see in Shopify.
    $ctr = array_key_exists('_ctr', $values) ? $values['_ctr'] : weekly_row_ctr($values);
    if ($ctr !== null) {
        $boxes[] = [weekly_percent($ctr), 'Average CTR', null];
    }
    $position = $values['gsc_position'] ?? null;
    if ($position !== null && $position !== '') {
        $boxes[] = [rtrim(rtrim(number_format((float) $position, 1, '.', ''), '0'), '.'), 'Average Position', null];
    }
    return $boxes;
}

// A week's click-through rate: the CTR typed for that week when there is one,
// otherwise worked out from its clicks and impressions.
function weekly_row_ctr(array $row): ?float
{
    $typed = $row['gsc_ctr'] ?? null;
    if ($typed !== null && $typed !== '') {
        return (float) $typed;
    }
    return weekly_rate($row['organic_clicks'] ?? null, $row['search_impressions'] ?? null);
}

function weekly_rate($part, $whole): ?float
{
    if ($part === null || $part === '' || $whole === null || $whole === '' || (float) $whole <= 0) {
        return null;
    }
    return (float) $part / (float) $whole * 100;
}

function weekly_percent(float $v): string
{
    return rtrim(rtrim(number_format($v, 2), '0'), '.') . '%';
}

// A case study's stats for the combined totals: once it has weekly data,
// its weekly totals replace the matching "Results At A Glance" stats; any
// other manual stats (e.g. "Keywords At #1") still count as before.
function case_study_combined_stats(array $row): array
{
    $manual = [];
    foreach ([1, 2, 3, 4] as $n) {
        $value = trim((string) ($row["stat{$n}_value"] ?? ''));
        $label = trim((string) ($row["stat{$n}_label"] ?? ''));
        if ($value !== '' || $label !== '') {
            $manual[] = [$value, $label];
        }
    }
    $summary = isset($row['id']) ? summarise_weekly_stats(fetch_weekly_stats((int) $row['id'])) : null;
    if (!$summary) {
        return $manual;
    }
    $tracked = array_map('strtolower', array_map(fn($m) => WEEKLY_METRICS[$m], WEEKLY_COMBINED_METRICS));
    $stats = array_values(array_filter($manual, fn($s) => !in_array(strtolower($s[1]), $tracked, true)));
    foreach (WEEKLY_COMBINED_METRICS as $metric) {
        if ($summary['totals'][$metric] !== null) {
            $stats[] = [format_weekly_value($metric, $summary['totals'][$metric]), WEEKLY_METRICS[$metric]];
        }
    }
    return $stats;
}

// End date of the most recent week entered for any case study, or null.
function weekly_stats_latest_end(): ?string
{
    $latest = null;
    foreach (fetch_all_weekly_stats() as $rows) {
        foreach ($rows as $row) {
            $end = $row['week_end'] ?: $row['week_start'];
            if (!(int) $row['is_baseline'] && $end && ($latest === null || $end > $latest)) {
                $latest = $end;
            }
        }
    }
    return $latest;
}

// Adds any metric column the table doesn't have yet (e.g. a metric added
// after the table was first set up). Run from the admin's Weekly Stats page.
function weekly_stats_ensure_columns(PDO $pdo): void
{
    foreach (['gsc_ctr', 'gsc_position'] as $extra) {
        try {
            $pdo->query("SELECT $extra FROM case_study_weekly_stats LIMIT 1");
        } catch (PDOException $e) {
            $pdo->exec("ALTER TABLE case_study_weekly_stats ADD COLUMN $extra DECIMAL(6,2) NULL");
        }
    }
    foreach (array_keys(WEEKLY_METRICS) as $metric) {
        try {
            $pdo->query("SELECT $metric FROM case_study_weekly_stats LIMIT 1");
        } catch (PDOException $e) {
            $pdo->exec("ALTER TABLE case_study_weekly_stats ADD COLUMN $metric DECIMAL(14,2) NULL");
        }
    }
}

// One-time setup from the admin: creates the table if missing, then gives
// every case study without weekly data a starting entry holding its current
// stats, so totals continue from today's numbers. Safe to run again -- case
// studies that already have weekly rows are left alone. Returns how many
// starting entries were added.
function weekly_stats_setup(PDO $pdo): int
{
    $pdo->exec(weekly_stats_create_table_sql());

    $labelToMetric = [];
    foreach (['organic_clicks', 'search_impressions', 'gbp_interactions', 'gbp_calls'] as $metric) {
        $labelToMetric[strtolower(WEEKLY_METRICS[$metric])] = $metric;
    }
    $existing = $pdo->query('SELECT DISTINCT case_study_id FROM case_study_weekly_stats')->fetchAll(PDO::FETCH_COLUMN);
    $existing = array_map('intval', $existing);

    $cols = array_keys(WEEKLY_METRICS);
    $insert = $pdo->prepare('INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, ' . implode(', ', $cols) . ') VALUES (?, 1, ?, ' . implode(', ', array_fill(0, count($cols), '?')) . ')');

    $added = 0;
    foreach ($pdo->query('SELECT * FROM case_studies')->fetchAll() as $study) {
        if (in_array((int) $study['id'], $existing, true)) {
            continue;
        }
        $values = array_fill_keys($cols, null);
        foreach ([1, 2, 3, 4] as $n) {
            $metric = $labelToMetric[strtolower(trim((string) ($study["stat{$n}_label"] ?? '')))] ?? null;
            $number = parse_weekly_number($study["stat{$n}_value"] ?? '');
            if ($metric && $number !== null && stat_value_format((string) $study["stat{$n}_value"]) === 'number') {
                $values[$metric] = $number;
            }
        }
        foreach (['shop_sales' => 'shop_sales', 'shop_orders' => 'shop_orders', 'shop_sessions' => 'shop_sessions'] as $metric => $field) {
            if (isset($study[$field])) {
                $values[$metric] = parse_weekly_number($study[$field]);
            }
        }
        if (count(array_filter($values, fn($v) => $v !== null)) === 0) {
            continue;
        }
        $label = trim((string) ($study['period'] ?? '')) ?: 'Before weekly tracking';
        $insert->execute([(int) $study['id'], mb_substr($label, 0, 100), ...array_values($values)]);
        $added++;
    }
    return $added;
}

// "6.66K" style text for the case study page's own stat fields (below 1,000
// as a plain number, then K / M with up to 2 decimals).
function weekly_short_number(float $v): string
{
    $abs = abs($v);
    if ($abs < 1000) {
        return rtrim(rtrim(number_format($v, 1, '.', ''), '0'), '.');
    }
    if ($abs < 1000000) {
        return rtrim(rtrim(number_format($v / 1000, 2, '.', ''), '0'), '.') . 'K';
    }
    return rtrim(rtrim(number_format($v / 1000000, 2, '.', ''), '0'), '.') . 'M';
}

// True when $weekStart is the latest week entered for this case study, so
// editing an old week never overwrites the current Search Console / Business
// Profile sections.
function weekly_is_latest_week(PDO $pdo, int $studyId, string $weekStart): bool
{
    $stmt = $pdo->prepare('SELECT MAX(week_start) FROM case_study_weekly_stats WHERE case_study_id = ? AND is_baseline = 0');
    $stmt->execute([$studyId]);
    $latest = $stmt->fetchColumn();
    return !$latest || $weekStart >= $latest;
}

// A screenshot uploaded with a week is proof for that week's numbers, so it
// also refreshes the case study page's "Google Search Console Performance" /
// "Google Business Profile Performance" sections to the same figures.
// $gsc / $gbp are the uploaded image paths (null = no screenshot, leave that
// section alone). $v holds the week's metric values, $extra the CTR and
// position typed next to them. Only fields that have a value are changed.
function weekly_sync_case_sections(PDO $pdo, int $studyId, array $v, array $extra, ?string $gsc, ?string $gbp): void
{
    $set = [];
    $args = [];
    $put = function (string $column, ?string $text) use (&$set, &$args): void {
        if ($text !== null && $text !== '') {
            $set[] = "$column = ?";
            $args[] = $text;
        }
    };
    $num = fn(string $k): ?string => ($v[$k] ?? null) === null ? null : weekly_short_number((float) $v[$k]);

    if ($gsc !== null) {
        $clicks = $v['organic_clicks'] ?? null;
        $impr = $v['search_impressions'] ?? null;
        $ctr = parse_weekly_number($extra['gsc_ctr'] ?? '');
        if ($ctr === null) {
            $ctr = weekly_rate($clicks, $impr);
        }
        $put('gsc_clicks', $num('organic_clicks'));
        $put('gsc_impressions', $num('search_impressions'));
        $put('gsc_ctr', $ctr === null ? null : rtrim(rtrim(number_format($ctr, 2, '.', ''), '0'), '.'));
        $pos = parse_weekly_number($extra['gsc_position'] ?? '');
        $put('gsc_position', $pos === null ? null : rtrim(rtrim(number_format($pos, 1, '.', ''), '0'), '.'));
        $put('gsc_image', $gsc);
    }
    if ($gbp !== null) {
        $put('gbp_interactions', $num('gbp_interactions'));
        $put('gbp_calls', $num('gbp_calls'));
        $put('gbp_directions', $num('gbp_directions'));
        $put('gbp_website_clicks', $num('gbp_website_clicks'));
        $put('gbp_image', $gbp);
    }
    if ($set) {
        $args[] = $studyId;
        $pdo->prepare('UPDATE case_studies SET ' . implode(', ', $set) . ' WHERE id = ?')->execute($args);
    }
}

// Saves one week for a case study from the "all case studies" page. If the
// week (same start date) already exists, only the numbers typed this time are
// changed and the rest are kept, so Search Console can be entered first and
// Business Profile added later without wiping anything. Returns 'inserted'
// or 'updated'. $values is metric => float|null.
function weekly_save_week_merge(PDO $pdo, int $studyId, string $start, string $end, array $values, ?float $ctr = null, ?float $position = null): string
{
    $given = array_filter($values, fn($v) => $v !== null);
    $stmt = $pdo->prepare('SELECT id FROM case_study_weekly_stats WHERE case_study_id = ? AND is_baseline = 0 AND week_start = ? LIMIT 1');
    $stmt->execute([$studyId, $start]);
    $existing = $stmt->fetchColumn();
    if ($existing) {
        if ($given) {
            $sets = implode(', ', array_map(fn($m) => "$m = ?", array_keys($given)));
            $pdo->prepare("UPDATE case_study_weekly_stats SET week_end = ?, $sets WHERE id = ?")->execute([$end, ...array_values($given), (int) $existing]);
        }
        weekly_store_ctr_position($pdo, (int) $existing, $ctr, $position, false);
        return 'updated';
    }
    $cols = array_keys(WEEKLY_METRICS);
    $pdo->prepare('INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, week_start, week_end, ' . implode(', ', $cols) . ') VALUES (?, 0, \'\', ?, ?, ' . implode(', ', array_fill(0, count($cols), '?')) . ')')
        ->execute([$studyId, $start, $end, ...array_map(fn($m) => $values[$m] ?? null, $cols)]);
    weekly_store_ctr_position($pdo, (int) $pdo->lastInsertId(), $ctr, $position, false);
    return 'inserted';
}

// Saves the optional CTR / average position typed with a week. $replace true
// (single-week form) overwrites both, blank meaning "work CTR out from the
// numbers"; false (bulk page) only changes the ones that were typed.
function weekly_store_ctr_position(PDO $pdo, int $rowId, ?float $ctr, ?float $position, bool $replace = true): void
{
    if ($rowId <= 0) {
        return;
    }
    $sets = [];
    $args = [];
    foreach (['gsc_ctr' => $ctr, 'gsc_position' => $position] as $col => $val) {
        if ($replace || $val !== null) {
            $sets[] = "$col = ?";
            $args[] = $val;
        }
    }
    if ($sets) {
        $args[] = $rowId;
        try {
            $pdo->prepare('UPDATE case_study_weekly_stats SET ' . implode(', ', $sets) . ' WHERE id = ?')->execute($args);
        } catch (PDOException $e) {
            // columns not added yet: the number still counts, CTR is just computed
        }
    }
}
