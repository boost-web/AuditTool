<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <body>
        <form method="get" action="audit_tool.php">
          <input type="text" name="domain" />
          <input type="submit" name="submit" value="submit" />
        </form>

<?php
    include 'audit_class.php';
    if (isset($_GET['domain'])) {
        $audit = new audit($_GET['domain']);
        $audit->printAll();
    }
?>
    </body>
</html>