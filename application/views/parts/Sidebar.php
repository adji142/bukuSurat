<!--sidebar-menu-->
<?php
  $active = '';
  $temp_lv1 = $this->GlobalVar->GetSideBar($user_id,0,0)->result();
?>
<style type="text/css">
  .separator-custom{
    color: rgb(255,64,64);
  }
</style>
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li><a href="<?= base_url(); ?>"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    <?php
      foreach ($temp_lv1 as $key) {
        $parent_id = $key->id;
        $temp_lv2 = $this->GlobalVar->GetSideBar($user_id,$parent_id,0)->result();

        if ($key->multilevel == "0") {
          if($key->separator == "0"){
            echo "<li><a href='".base_url().$key->link."'><i class='icon ".$key->ico."'></i> <span>".$key->permissionname."</span></a> </li>";
          }
          else{
            echo "<li class ='content'><center><span class ='separator-custom'> ".$key->permissionname."</span></center></li>";  
          }
        }
        else{
          echo "<li class='submenu'>";
          echo "<a href='".base_url().$key->link."'><i class='icon ".$key->ico."'></i> <span>".$key->permissionname."</span></a>";
          echo "<ul>";
          foreach ($temp_lv2 as $child) {
            if($child->separator == "0"){
              echo "<li><a href='".base_url().$child->link."'>".$child->permissionname."</a></li>";
            }
            else{
              echo "<li class ='content'><center><span class ='separator-custom'> ".$child->permissionname."</span></center></li>";
            }
          }
          echo "</ul>";
          echo "</li>";
        }
      }
    ?>
  </ul>
</div>