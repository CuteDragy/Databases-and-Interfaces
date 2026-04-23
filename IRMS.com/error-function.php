<?php 
    $error_msg = (isset($_GET['status']) && $_GET['status'] === 'error' && isset($_GET['msg'])) 
                ? htmlspecialchars($_GET['msg']) : null;
?>
<script>
    const error_msg = <?php echo $error_msg ? '"' . addslashes($error_msg) . '"' : 'null'; ?>;
    window.addEventListener('load', function() {
        if (error_msg !== null) {
            window.alert(error_msg);
        }
    });
</script>