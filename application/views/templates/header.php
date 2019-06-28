<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Schikane</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/icons/images/favicon-16x16.png">
    <link rel="stylesheet" type="text/css" href='https://bootswatch.com/4/superhero/bootstrap.min.css' >
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/main.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
</head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">Schikane</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url(); ?>">Home </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>game">Spielersuche</a>
                </li> 
                <ul class="navbar-nav nav ml-auto" >
                <?php if(!$this->session->userdata('logged_in')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>users/login">Anmelden</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>users/register">Registrieren</a>
                    </li>
                <?php endif; ?>
                <?php if($this->session->userdata('logged_in')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>users/logout">Abmelden</a>
                    </li>
                <?php endif; ?>
                </ul>
            </div>
        </nav>
        
            <?php if($this->session->flashdata('user_registered')) : ?>
                <?php echo "<p class='alert alert-success'>".$this->session->flashdata('user_registered')."</p>"; ?>
            <?php endif; ?>
            <?php if($this->session->flashdata('user_loggedin')) : ?>
                <?php echo "<p class='alert alert-success'>".$this->session->flashdata('user_loggedin')."</p>"; ?>
            <?php endif; ?>
            <?php if($this->session->flashdata('login_failed')) : ?>
                <?php echo "<p class='alert alert-danger'>".$this->session->flashdata('login_failed')."</p>"; ?>
            <?php endif; ?>
            <?php if($this->session->flashdata('user_loggedout')) : ?>
                <?php echo "<p class='alert alert-success'>".$this->session->flashdata('user_loggedout')."</p>"; ?>
            <?php endif; ?>
           
