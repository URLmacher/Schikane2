<nav class="navbar navbar-expand-lg " id="navbar">
    <a class="navbar-brand" href="<?php echo base_url(); ?>">
        <img src="<?php echo base_url(); ?>assets/images/logo.svg" alt="logo-navigation" />
    </a> 
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
                <a class="nav-link" id="dashboard-btn" href="#">Dashboard</a>
            </li>
            <li class="nav-item nav-right">
                <a class="nav-link" href="<?php echo base_url(); ?>users/logout">Abmelden</a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
    <div class="navbar-username" id="username-to-dashboard"><?php echo $this->session->userdata('user_name'); ?></div>
</nav>
<div class="dashboard-close-btn-nav hide" id="dashboard-close-btn-nav"></div>