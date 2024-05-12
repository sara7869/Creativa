<style type="text/css">
    body {
            background-image: url('https://images.unsplash.com/photo-1629375286699-8faeaa9aab59?q=80&w=1776&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }

    navbar-item {
        color: white !important;
        margin-top: auto;
        margin-bottom: auto;
    }

</style>
<div class="ui secondary pointing menu" style="background-color: black; ">
    <h2 class="item" style="color: white !important;">
        Creativa
    </h2>
    <a class="item" href="<?php echo site_url('/SiteController/timelinePage'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
        Timeline
    </a>
    <a class="item" href="<?php echo site_url('/SiteController/homePage'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
        Home
    </a>
    <a class="item" href="<?php echo site_url('/SiteController/searchPage'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
        Search
    </a>
    <a class="item" href="<?php echo site_url('/SiteController/connections'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
        Connections
    </a>
    <a class="item" href="<?php echo site_url('/SiteController/contactsList'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
        Contacts List
    </a>
    <div class="right menu">
        <a class="ui item" href="<?php echo site_url('/UserController/logoutUser'); ?>" style="color: white !important; margin-top: auto; margin-bottom: auto;">
            Logout
        </a>
    </div>
</div>