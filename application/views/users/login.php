<div class="kastel2">
    <h1 class="kastel2__title"><?=$title ?></h1>

    <section class='kastel2__content'>
        <?php if(validation_errors()) {
            echo  '<div class="kastel2__error-box">';
            echo validation_errors('<p class="error">', '</p>'); 
            echo '</div>';
        }?>
        <?php echo form_open_multipart('users/login'); ?>
            <fieldset>
            
                <div class="form-group kastel2__form-group">
                    <label for="user_name">Username</label>
                    <input id='user_name' type="text" class="form-control" name='user_name' placeholder='Username' required autofocus autocomplete="username">
                </div>

                <div class="form-group kastel2__form-group">
                    <label for="password">Password</label>
                    <input id='password' type="password" class="form-control" name='password' required autofocus autocomplete="current_password">
                </div>

                <div class="kastel2__btn-box">
                    <button type="submit" class="btn ">Login</button>
                    <a class="kastel2__link" href="<?php echo base_url(); ?>users/register">Kein Konto?</a>
                </div>
                
            </fieldset>
        </form>
    </section>
    
</div>
