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
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/main.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/dashboard.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/game.css">
</head>
    <body>

        <div class="loading-screen" id="loading-screen">
            <img class="loading-screen__logo" src="assets\images\logo.svg" alt="logo-loadingscreen">
            <div class="loading-screen__loading-bar"></div>
        </div>
        <div id="message-container" class="message-container ">
            <div id="message-container__message-box" class="message-container__message-box">
                <h3 id="message-container__message" class="message-container__message "></h3>
            </div>
        </div>
        <div class="game-menu">
            <div id="game-info" class="game-menu__btn"></div>
            <div id="dashboard-btn" class="game-menu__btn"></div>
            <div id="game-leave" class="game-menu__btn"></div>
            <div id="dashboard-close-btn" class="game-menu__btn--close hide"></div>
        </div>
      

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
           
        <div id="async-feedback" class="async-feedback "></div>
           
       
    <?php
    $this->load->view('templates/dashboard');
    ?>
    <?php
    $this->load->view('templates/message-form');
    ?>
    <?php
    $this->load->view('templates/other-profile');
    ?>
    <?php
    $this->load->view('templates/game-info');
    ?>
    <?php
    $this->load->view('templates/game-leave');
    ?>
