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
                <a class="nav-link" id="dashboard-btn" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>users/logout">Abmelden</a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</nav>
        