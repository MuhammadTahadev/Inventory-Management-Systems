$(document).ready(function() {
    <?php if(isset($_SESSION['show_trial_modal']) && $_SESSION['show_trial_modal']): ?>
    $("#trialModal").show();
    <?php 
    // Reset the flag
    $_SESSION['show_trial_modal'] = false;
    endif; 
    ?>

    $("#closeModal").click(function() {
        $("#trialModal").hide();
        window.location.href = 'Dashboard-free.php';
    });
});