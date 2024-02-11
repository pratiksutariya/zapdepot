<!DOCTYPE html>
<html>
<head>
 <title>Lister Pro</title>
 <style>
     p{
         color:black;
     }
 </style>
</head>
<body>

 <h1><?php if($details['fname']): ?> <?php echo e($details['fname']); ?> <?php endif; ?> <?php if($details['lname']): ?> <?php echo e($details['lname']); ?> <?php endif; ?>, welcome to Zapdepot!</h1>
    <p>Welcome to Zapdepot!</p>

    <p>Congratulations on joining. Your possibilities are endless.</p>
    <p>Now, are you ready to conquer the Internet?</p>

    <p>Here are you contact details.</p>

    <p>UserName : <?php if($details['uname']): ?> <?php echo e($details['uname']); ?> <?php endif; ?> </p>
    <p>Password : <?php if($details['upass']): ?> <?php echo e($details['upass']); ?> <?php endif; ?> </p>

    <p>Have any questions about how to set up your account? Just hit reply and let us know.</p>
    <p>Thanks!</p>
    <p>Zapdepot Team</p>

</body>
</html>
<?php /**PATH /home/api.zapdepot.io/public_html/resources/views/send-mail.blade.php ENDPATH**/ ?>