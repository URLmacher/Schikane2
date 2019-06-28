<div class='container'>
    <div class="jumbotron">
        <h1 class="display-3"><?=$title ?></h1>
    </div>
    <p class="error"><?php echo validation_errors(); ?></p>
    <section class='register'>
    <section class='col-md-4 col-md-offset-4'>
        <?php echo form_open_multipart('users/login'); ?>
            <fieldset>
            
                <div class="form-group">
                <label for="user_name">Username</label>
                <input id='user_name' type="text" class="form-control" name='user_name' placeholder='Username' required autofocus autocomplete="username">
                </div>

                <div class="form-group ">
                <label for="password">Password</label>
                <input id='password' type="password" class="form-control" name='password' required autofocus autocomplete="current_password">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
            </fieldset>
        </form>
    </section>
</div>