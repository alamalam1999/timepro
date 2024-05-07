<aside id="left-sidebar-nav">
                <ul id="slide-out" class="side-nav fixed leftside-navigation">
                    <li class="user-details cyan darken-2">
                        <div class="row">
                            <div class="col col s4 m4 l4">
                                <img src="<?php echo $_SESSION['gambar']; ?>" alt="" style="border: 2px solid white;" class="circle responsive-img valign profile-image">
                            </div>
                            <div class="col col s8 m8 l8">
                                <ul id="profile-dropdown" class="dropdown-content">
                                    <li><a href="detail-admin.php?id=<?php echo $_SESSION['user_id']; ?>"><i class="mdi-action-face-unlock"></i> Profile</a>
                                    </li>
                                    <!--<li><a href="#"><i class="mdi-action-settings"></i> Settings</a>
                                    </li>
                                    <li><a href="#"><i class="mdi-communication-live-help"></i> Help</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a>
                                    </li>-->
                                    <li><a href="../logout.php"><i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                                    </li>
                                </ul>
                                <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><?php echo $_SESSION['fullname']; ?><i class="mdi-navigation-arrow-drop-down right"></i></a>
                                <p class="user-roal"><?php echo $_SESSION['level']; ?></p>
                            </div>
                        </div>
                    </li>
                    <!--<li class="bold"><a href="index.php" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>-->
                    <!--</li>-->
                     <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="index.php">Data Tiket</a></li>   
                                        <li><a href="index-gsuite.php">Data TIket G-Suite</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li> 
                   <?php
                        include "../conn.php";
                
                        $tanggal = date("Y-m-d");
                        //  $tampil2=mysqli_query($koneksi, "select * from tiket where tanggal='$tanggal' and status='open'");
                        $tampil2 = mysqli_query($koneksi, "SELECT id_tiket FROM `tiket_gsuite` WHERE status = 'new' and tanggal ='$tanggal'  UNION SELECT id_tiket FROM `tiket` WHERE status = 'new' and tanggal ='$tanggal'");
                        $total2 = mysqli_num_rows($tampil2);
                    ?>
                    <li class="no-padding">

                    <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-communication-email"></i> Kalender Kerja <span class="new badge count"></span></a>
                                <div class="collapsible-body">
                                    
                                </div>
                            </li>
                        </ul>


                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-communication-email"></i> Timeline Project <span class="new badge count"></span></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="input_project.php">Input Project</a></li>
                                        <li><a href="index.php">Timeline Project</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>   
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-social-person"></i> Admin </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="admin.php">Data Admin</a>
                                        </li>                                        
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>   
                </ul>
                <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i class="mdi-navigation-menu" ></i></a>
            </aside>
            
            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>


<script>
    $(document).ready(function() {

        console.log("testing");


        console.log("oke");

        function playSound(url) {
            const audio = new Audio(url);
            audio.play();
        }

        function load_unseen_notification() {

            $.ajax({
                url: "fetch.php",
                method: "POST",
                dataType: "json",
                success: function(data) {

                    console.log('oke' + data);
                    $('.dropdown-menu').html(data.notification);
                    if (data.unseen_notification > 0) {
                        console.log('masuk ke unseen');
                        $('.count').html(data.unseen_notification);
                        playSound('https://notificationsounds.com/storage/sounds/file-sounds-1215-strong-minded.mp3');
                    }

                    console.log("notif ada = " + data.unseen_notification);
                }
            });

        }

        load_unseen_notification();

        setInterval(function() {
            load_unseen_notification();
        }, 5000);

    });
</script>