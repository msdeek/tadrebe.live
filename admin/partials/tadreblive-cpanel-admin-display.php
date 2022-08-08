<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.coderfish.com.eg
 * @since      1.0.0
 *
 * @package    tadreblive
 * @subpackage tadreblive/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div>
        <h2>CPanel Settings</h2>
        <form method="POST" action="options.php">
            <?php
            settings_fields( 'cpcred' );
            do_settings_sections('cpcred');
            ?>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="cpurl">CPanel URL</label>
                        </th>
                        <td>
                            <input 
                                name="cpurl" 
                                value="<?php echo get_option('cpurl');?>"
                                type="url"
                                id="cpurl"
                                aria-describedby="urlHelpInline"
                                class="regular-text code disabled"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cpusername">CPanel username</label>
                        </th>
                        <td>
                            <input 
                                name="cpusername" 
                                value="<?php echo get_option('cpusername');?>"
                                type="text"
                                id="cpusername"
                                aria-describedby="urlHelpInline"
                                class="regular-text code disabled"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cppassword">CPanel password</label>
                        </th>
                        <td>
                            <input 
                                name="cppassword" 
                                value="<?php echo get_option('cppassword');?>"
                                type="password"
                                id="cppassword"
                                aria-describedby="urlHelpInline"
                                class="regular-text code disabled"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cpurl">CPanel Service</label>
                        </th>
                        <td>
                            <input 
                                name="cpservice" 
                                value="<?php echo get_option('cpservice');?>"
                                type="text"
                                id="cpservice"
                                aria-describedby="urlHelpInline"
                                class="regular-text code disabled"
                            />
                        </td>
                        <td> 
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cpurl">Token</label>
                        </th>
                        <td>
                            <input 
                                name="token" 
                                value="<?php echo get_option('cptoken');?>"
                                type="text"
                                id="token"
                                aria-describedby="urlHelpInline"
                                class="regular-text code disabled"
                                readonly
                            />
                        </td>
                        <td> 
                            
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                        </th>
                        <td>
                           <button type="submit" class="btn-sm">Save</button>
                           <button id="test_connection_button" type="button" class="btn-sm">Test</button>
                           <span class="response-box"></span>

                        </td>
                        
                        
                    </tr>
                    <tr>
                    <th></th><td> <div id="test_connection_response"></div> </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <div>
       
                </div>
            </div>
        </form>
    </div>
</div>

