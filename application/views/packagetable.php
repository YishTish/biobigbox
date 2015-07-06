<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

            <table class="table table-package">
                <thead>
                    <tr><th class="percent40">&nbsp;</th>
                        <?php foreach ($packages as $p) 
                                if ($user && ($p->id == $user->packageid))
                                    echo '<th class="currentpackage center">YOUR CURRENT PACKAGE<br /><img src="img/OrangeArrowDown.png" alt="" /></th>';
                                else
                                    echo '<th>&nbsp;</th>'; 
                        ?>
                    </tr>
                    <tr><th>Package</th>
                        <?php foreach ($packages as $p) 
                                echo '<th class="percent15' . ($user && ($p->id == $user->packageid) ? ' currentpackage' : '') . '">' . $p->name . '</th>'; 
                        ?>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <?php foreach ($packages as $p) {
                            echo '<th' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->id == 4)
                                echo '<img src="' . site_url('img/comingsoon2.png') . '" width="100" />';
                            else if ($user) {
                                if ($p->id > $user->packageid) 
                                    echo '<a class="btn btn-warning btn-black" href="' . site_url('users/upgrade/' . $p->id) . '">Upgrade</a>';
                            } else {
                                if ($p->id == 1)
                                    echo '<a class="btn btn-warning btn-black" href="#" data-toggle="modal" data-target="#registerform">Sign Up</a>';
                                else
                                    echo '<a class="btn btn-warning btn-black" href="' . site_url('home/purchase/' . $p->id) . '">Subscribe</a>';
                            }
                            echo '</th>';
                        } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Price/month</td>
                        <?php foreach ($packages as $p) 
                                echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>' . ($p->price != 0 ? '$' . number_format($p->price) : 'Free') . '</td>'; ?>
                    </tr>
                    <tr><td>File storage</td>
                        <?php foreach ($packages as $p) 
                                echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>' . ($p->storage ? $p->storage : 'Unlimited') . ' files</td>'; ?>
                    </tr>
                    <tr><td>Storage duration</td>
                        <?php foreach ($packages as $p) 
                                echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>' . ($p->expiry ? $p->expiry . ' days' : 'Lifetime') . '</td>'; ?>
                    </tr>
                    <tr><td>Vault can receive files (others can share files with you)</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->receiveshare)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Place files into another vault (you can share files with others)</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->sendshare)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Deleted file recovery (<?php echo $this->config->item('deleted_file_recovery_days'); ?> days)</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->recovery)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Basic online file viewing (PDF, image, Word, Excel)</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->basicviewing)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Advanced online file viewing (Video, STL, DICOM)</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->advancedviewing)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Email notification of shared file</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->emailnotify)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>SMS notification of shared file</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->smsnotify)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr><td>Confirmation email/SMS when shared file is viewed</td>
                        <?php foreach ($packages as $p) {
                            echo '<td' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->shareconfirm)
                                echo '<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>';
                            else
                                echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>';
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <?php foreach ($packages as $p) {
                            echo '<th' . ($user && ($p->id == $user->packageid) ? ' class="currentpackage"' : '') . '>';
                            if ($p->id == 4)
                                echo '<img src="' . site_url('img/comingsoon2.png') . '" width="100" />';
                            else if ($user) {
                                if ($p->id > $user->packageid) 
                                    echo '<a class="btn btn-warning btn-black" href="' . site_url('users/upgrade/' . $p->id) . '">Upgrade</a>';
                            } else {
                                if ($p->id == 1)
                                    echo '<a class="btn btn-warning btn-black" href="#" data-toggle="modal" data-target="#registerform">Sign Up</a>';
                                else
                                    echo '<a class="btn btn-warning btn-black" href="' . site_url('home/purchase/' . $p->id) . '">Subscribe</a>';
                            }
                            echo '</th>';
                        } ?>
                    </tr>
                </tbody>
            </table>
