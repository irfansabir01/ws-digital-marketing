<?php
// Shared right-column enquiry form for every "Ready To...?" CTA section.
// Included inside a .cta-form-wrap; expects includes/cta-form-handler.php to
// have already run higher up the page. The including page may set
// $cta_button_label (submit button text), $cta_default_service (service
// pre-selected in the dropdown), $cta_plan (an already-validated plan name
// carried as a hidden field, e.g. from pricing.php's plan buttons) and
// $cta_prefill_message (starting text for the message box) before including
// this file.
$cta_button_label = $cta_button_label ?? 'REQUEST MY FREE GROWTH PLAN';
$cta_default_service = $cta_default_service ?? '';
$cta_selected_service = $_POST['service'] ?? $cta_default_service;
$cta_plan = $_POST['plan'] ?? $cta_plan ?? '';
$cta_prefill_message = $cta_prefill_message ?? '';
$cta_message_value = $_POST['message'] ?? $cta_prefill_message;
$cta_services = [
    'Search Engine Optimisation',
    'Ecommerce',
    'Website Design & Development',
    'Social Media Marketing',
    'Paid Advertising',
    'Graphic Design',
    'Content Writing',
];
$cta_form_action = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8') . '#contact';
?>
<?php if ($cta_email_sent): ?>
    <div class="cta-form-message cta-form-success">
        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> Thank you! Your message has been successfully sent. We will get back to you shortly.
    </div>
<?php elseif ($cta_form_error): ?>
    <div class="cta-form-message cta-form-error">
        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> <?php echo htmlspecialchars($cta_form_error, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!$cta_email_sent): ?>
<form toolname="request_free_growth_plan" tooldescription="Send an enquiry to W&amp;S Digital Marketing (Australian digital marketing agency) to request a free growth plan or a quote. Only submit with the user's confirmation." action="<?php echo $cta_form_action; ?>" method="POST" class="cta-enquiry-form">
    <?php if ($cta_plan !== ''): ?>
    <input type="hidden" name="plan" toolparamdescription="Pricing plan the user is enquiring about (Starter, Growth or Scale)" value="<?php echo htmlspecialchars($cta_plan, ENT_QUOTES, 'UTF-8'); ?>">
    <p class="cta-enquiry-plan-note"><i class="fa-solid fa-circle-check" style="color: var(--cyan-neon); margin-right: 6px;"></i>Enquiring about the <?php echo htmlspecialchars($cta_plan, ENT_QUOTES, 'UTF-8'); ?> Plan</p>
    <?php endif; ?>
    <div class="cta-enquiry-row">
        <div class="cta-enquiry-field">
            <label for="cta-name">Your Name *</label>
            <input type="text" id="cta-name" name="name" toolparamdescription="The user&#39;s full name" placeholder="John Smith" required value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="cta-enquiry-field">
            <label for="cta-email">Email Address *</label>
            <input type="email" id="cta-email" name="email" toolparamdescription="Email address the agency should reply to" placeholder="john@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
    </div>
    <div class="cta-enquiry-row">
        <div class="cta-enquiry-field">
            <label for="cta-phone">Phone Number (optional)</label>
            <input type="tel" id="cta-phone" name="phone" toolparamdescription="Optional contact phone number, e.g. 0400 000 000" placeholder="0400 000 000" value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="cta-enquiry-field">
            <label for="cta-service">Service Needed</label>
            <select id="cta-service" name="service" toolparamdescription="The marketing service the user needs">
                <?php foreach ($cta_services as $cta_service_option): ?>
                <option value="<?php echo htmlspecialchars($cta_service_option, ENT_QUOTES, 'UTF-8'); ?>"<?php echo ($cta_selected_service === $cta_service_option) ? ' selected' : ''; ?>><?php echo htmlspecialchars($cta_service_option, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
                <option value="Not sure / Multiple services"<?php echo ($cta_selected_service === '' || $cta_selected_service === 'Not sure / Multiple services') ? ' selected' : ''; ?>>Not sure / Multiple services</option>
            </select>
        </div>
    </div>
    <div class="cta-enquiry-field">
        <label for="cta-message">Tell Us About Your Project *</label>
        <textarea id="cta-message" name="message" toolparamdescription="The user&#39;s business goals, current challenges and what they want to achieve" rows="3" placeholder="Share your goals, current challenges, or what you'd like to achieve..." required><?php echo htmlspecialchars($cta_message_value, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    <p class="cta-enquiry-privacy">By submitting this form, you agree to our <a href="/privacy-policy">Privacy Policy</a>.</p>
    <button type="submit" class="btn-primary cta-enquiry-submit"><?php echo htmlspecialchars($cta_button_label, ENT_QUOTES, 'UTF-8'); ?> <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></button>
</form>
<?php endif; ?>
