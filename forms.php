<?php

/**
 * Plugin Name: Forms plugin
 * Plugin URI: localhost
 * Description: This plugin manage all forms datas
 * Version: 0.1
 * Author: Emilie
 * Author URI: emiliesappracone.be
 * License: GPL2
 */
class Form
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'forms'));
    }

    /**
     * Router
     */
    public function forms()
    {
        add_menu_page('Forms', 'Forms', 'manage_options', 'forms', array($this, 'formsSettingsPage'), 'dashicons-email-alt', 200);
    }

    /**
     * Controller
     */
    public function formsSettingsPage()
    {
        $subscribers = $this->getAllDatas('subscribe');
        $contacts = $this->getAllDatas('contact');
        $this->formsView($subscribers, $contacts);
    }

    /**
     * View
     * @param $subscribers
     * @param $contacts
     */
    public function formsView($subscribers, $contacts)
    {
        ?>
        <div style="padding: 2em; background-color: #AB9F9D;color:#fff;margin: 1em 2em 1em 1em;">
            <h1 style="color:#fff;font-size: 27px">Forms plugin</h1>
            <p style="font-size: 17px"><i>Welcome to the newsletters plugin, here you can download all forms datas.</i></p>
            <p style="color: #1B1B3A;">
                <small>
                    <ul>
                        <li style="color: #1B1B3A;background-color: bisque;border-radius: 2px;width: max-content;padding: 0.1em 1em;font-weight: bold">Be aware of the format file :</li>
                        <li style="color: #1B1B3A;color: #1B1B3A;background-color: bisque;border-radius: 2px;width: max-content;padding: 0.1em 1em;"><b style="font-weight: bold">' ; ' : </b> semicolon is used to separate data in a line</li>
                        <li style="color: #1B1B3A;color: #1B1B3A;background-color: bisque;border-radius: 2px;width: max-content;padding: 0.1em 1em;"><b style="font-weight: bold">' [\n] ' : </b> line feed is used to separate lines</li>
                    </ul>
                </small>
            </p>
        </div>
        <div style="display: flex;flex-direction: column;margin: 1em 2em 1em 1em">
                <div style="border: 1px solid #AB9F9D;margin:2em 0;"><h2 style="color: #AB9F9D;font-size: 16px;padding-left: 1.5em"><span class="dashicons dashicons-admin-users"></span> Subscribers <a href="<?php echo get_template_directory_uri() . '/csv/subscribe.csv'; ?>" style="background-color: #AB9F9D;color: #fff;padding: 0.3em 1em;border-radius: 5px;font-size: 10px" download="subscribers">Download csv</a></h2>
                    <table class="subscriber widefat" >
                    <thead>
                    <tr>
                        <th>Full name</th>
                        <th>Email address</th>
                        <th>Phone Number</th>
                        <th>Added at</th>
                    </tr>
                    </thead>
                    <tbody id="the-list">
                    <?php if(!$subscribers){ echo '<tr><td colspan="4">No Subscribers !</td></tr>'; }else{?>
                    <?php foreach ($subscribers as $subsriber) : ?>
                        <tr>
                            <td>
                                <small><?php echo $subsriber[0] ?></small>
                            </td>
                            <td>
                                <small><?php echo $subsriber[1] ?></small>
                            </td>
                            <td>
                                <small><?php echo $subsriber[2] ?></small>
                            </td>
                            <td>
                                <small><?php $date = date("d/m/Y - H:i", strtotime($subsriber[3]));
                                    echo $date; ?></small>
                            </td>
                        </tr>
                    <?php endforeach;}  ?>
                    </tbody>
                </table>
            </div>
            <div style="border: 1px solid #AB9F9D;margin:2em 0;"><h2 style="color: #AB9F9D;font-size: 16px;padding-left: 1.5em"><span class="dashicons dashicons-email-alt"></span> Contacts <a href="<?php echo get_template_directory_uri() . '/csv/contact.csv'; ?>" style="background-color: #AB9F9D;color: #fff;padding: 0.3em 1em;border-radius: 5px;font-size: 10px" download="contact">Download csv</a></h2>
                <table class="contacts widefat">
                    <thead>
                    <tr>
                        <th>Full name</th>
                        <th>Email address</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Added at</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!$contacts){ echo '<tr><td colspan="5">No request received !</td></tr>'; }else{?>
                    <?php foreach ($contacts as $contact) : ?>
                        <tr>
                            <td>
                                <small><?php echo $contact[0] ?></small>
                            </td>
                            <td>
                                <small><?php echo $contact[1] ?></small>
                            </td>
                            <td>
                                <small><?php echo $contact[2] ?></small>
                            </td>
                            <td>
                                <small><?php echo htmlspecialchars_decode($contact[3]) ?></small>
                            </td>
                            <td>
                                <small><?php $date = date("d/m/Y - H:i", strtotime($contact[4]));
                                    echo $date; ?></small>
                            </td>
                        </tr>
                    <?php endforeach; } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }

    /**
     * Manager
     * @param $file
     * @return array
     */
    public function getAllDatas($file)
    {
        $datas = [];
        $dataFile = file_get_contents(get_template_directory() . "/csv/$file.csv");
        $dataFile = substr($dataFile, 0, -4);
        // line
        $lines = explode('[\n]', $dataFile);
        // add subscriber to array
        foreach ($lines as $line) {
            $datas[] = explode(';', $line);
        }
        // remove column name
        unset($datas[0]);
        return $datas;
    }

}

$forms = new Form();