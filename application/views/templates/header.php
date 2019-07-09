<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Schikane</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/icons/images/favicon-16x16.png"> -->
    <!-- <link rel="stylesheet" type="text/css" href='https://bootswatch.com/4/superhero/bootstrap.min.css' > -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/main.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.js" ></script>
    <script src="<?php echo base_url(); ?>assets/js/popper.min.js" ></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js" ></script>
</head>
<body>
                   
    <?php
    $this->load->view('templates/navbar');
    ?>

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
  
    <div id="async-feedback"></div>
           
       
    <?php
    $this->load->view('templates/dashboard');
    ?>
    <?php
    $this->load->view('templates/message-form');
    ?>