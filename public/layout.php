<?php
    $isInsert = isset($_GET['insert']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/vendor/bootstrap/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/vendor/jquery/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
        <style>
            .content {
                margin-top: 50px;
            }
            td.teama-total-score,
            td.teamb-total-score {
                font-weight: bold;
            }
            .err {
                background-color: #f00;
            }
        </style>
        <title>TT</title>
    </head>
    <body>
        <!-- navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top navbar-site" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                    </button>
                    <a class="navbar-brand" href="/">Home</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="<?php echo !$isInsert ? 'active' : '' ?>">
                            <a class="nav-home" href="/">Stats</a>
                        </li>
                        <li class="<?php echo $isInsert ? 'active' : '' ?>">
                            <a class="nav-home" href="/?insert=1">Insert</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- content -->
        <div class="content row">
            <div class="col-md-10 col-md-offset-1">
                <?php echo $content; ?>
            </div>
        </div>
    </body>
    <script type="text/javascript">
    </script>
</html>