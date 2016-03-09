<?php
if(!defined('MY_INDEX')) exit;

include('functions_recursive.php');
include ('function_event.php');
include_once('options.inc.php');


if(isset($_REQUEST['action']) && $_REQUEST['action'] != "")
     $action = $_REQUEST['action']; else $action = "search";

$ev_id = (!empty($_REQUEST['ev_id'])) ? $_REQUEST['ev_id'] :

            (
            (!empty($_REQUEST['event']['ev_id'])) ? $_REQUEST['event']['ev_id'] : NULL
            );

$op = (!empty($_REQUEST['op'])) ? $_REQUEST['op'] : NULL;


if($options['debug'])
{
    echo "print REQUEST <br/>";
    print_r($_REQUEST);
}

if(!$ev_id)
{
    if($action != 'admin')
    $action = 'search';
}
else{
    //if($user_type == 'admin') $action = 'admin';
    if($action != 'admin' && $action != 'set_person' && $action != 'set_repertory' && $action != 'set_per2rep' && $action != 'set_details') $action = 'view';
    if(!$op) $op = 'view'; //poate nu aici
}

if($action == 'search')
{
    header("Location: index.php?mode=season&action=search");
    exit;
}

if($action == 'preview'){
    // vizualizare evenimente daca sunt multe rezultate.
    // daca e unul singur schimba in view
    // location op = view
}

if($action != 'search' || $action != 'preview')
{
     // menu admin
    if($user_type == 'admin' || $user_type == 'editor')
    {
    echo '<div class="row">';
    echo '<div class="col-xs-3 col-sm-6 col-md-2">';
    echo '<a href="index.php?mode=event&action=admin&op=view" class="btn btn-xs btn-primary">New</a>'."\n";
    echo '<a href="index.php?mode=event&action=admin&op=view&ev_id='.$ev_id.'" class="btn btn-xs btn-success">Edit</a>'."\n";
    echo '<a href="index.php?mode=event&action=admin&op=del&ev_id='.$ev_id .'" class="btn btn-xs btn-danger" >Del</a>'."\n";
    //sterge
   echo '</div>';

    echo '<div class="col-xs-3 col-sm-6 col-md-2">';
    echo '<a href="index.php?mode=event&action=set_person&op=view&ev_id='.$ev_id.'" class="btn btn-xs btn-primary">SetPerson</a>'."\n";
    echo '<br/><form class="navbar-form " style="display: inline;" method="get" action="index.php" id="newPerson">
            SET
            <input type="search" name="records" style="width: 15px; " >
            <input type="hidden" name="mode" value="event">
            <input type="hidden" name="action" value="set_person">
            <input type="hidden" name="op" value="new">
            <input type="hidden" name="ev_id" value="'.$ev_id.'">
            <button type="submit" class="btn btn-xs btn-primary">NewPerson</button>

            </form>
            ';
    echo '</div>';
    echo '<div class="col-xs-3 col-sm-6 col-md-2">';
    echo '<a href="index.php?mode=event&action=set_repertory&op=view&ev_id='.$ev_id.'" class="btn btn-xs btn-primary">SetRepertory</a>'."\n";
    echo '<br/>
            <form class="navbar-form " style="display: inline;" method="get" action="index.php" id="newRepertory">
            SET
            <input type="search" name="records" style="width: 15px;" >
            <input type="hidden" name="mode" value="event">
            <input type="hidden" name="action" value="set_repertory">
            <input type="hidden" name="op" value="new">
            <input type="hidden" name="ev_id" value="'.$ev_id.'">
            <button type="submit" class="btn btn-xs btn-primary">NewTitle(s)</button>

            </form>
            ';
    echo '</div>';


        //Set details
    echo '<div class="col-xs-3 col-sm-6 col-md-2">';
    echo '<a href="index.php?mode=event&action=set_details&op=view&ev_id='.$ev_id.'" class="btn btn-xs btn-primary">SetDetail(s)</a>'."\n";
    echo '<br/>
            <form class="navbar-form " style="display: inline;" method="get" action="index.php" id="newDetail">
            SET
            <input type="search" name="records" style="width: 15px;" >
            <input type="hidden" name="mode" value="event">
            <input type="hidden" name="action" value="set_details">
            <input type="hidden" name="op" value="new">
            <input type="hidden" name="ev_id" value="'.$ev_id.'">
            <button type="submit" class="btn btn-xs btn-primary">NewDetail(s)</button>

            </form>
            ';
    echo '</div>';


    echo '<div class="col-xs-3 col-sm-6 col-md-3">';
    $prev = $db->query("Select ev_id from event where ev_id < ".$ev_id."  order by ev_id desc limit 1");
    if($prev)
    { $prev_id = $prev->fetch(PDO::FETCH_ASSOC);
    echo '<a href="index.php?mode=event&action=admin&op=view&ev_id='.$prev_id['ev_id'].'" class="btn btn-xs btn-primary">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
    &nbsp;ev_id '.$prev_id['ev_id'].'</a>'."\n";
    echo '<a href="index.php?mode=event&op=view&ev_id='.$prev_id['ev_id'].'" class="btn btn-xs btn-primary">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>

    &nbsp;ev_id '.$prev_id['ev_id'].'</a>'."\n";
    }

    echo '<br/>';

    $next = $db->query("Select ev_id from event where ev_id > ".$ev_id." limit 1");
    if($next)
    {
    $next_id = $next->fetch(PDO::FETCH_ASSOC);
    echo '<a href="index.php?mode=event&action=admin&op=view&ev_id='.$next_id['ev_id'].'" class="btn btn-xs btn-primary">
    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    &nbsp;ev_id '.$next_id['ev_id'].'</a>'."\n";
    echo '<a href="index.php?mode=event&op=view&ev_id='.$next_id['ev_id'].'" class="btn btn-xs btn-primary">
    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    &nbsp;ev_id '.$next_id['ev_id'].'</a>'."\n";
    }
    echo '</div>';
     echo '</div>'; //row
    }

    if($ev_id) // show page of concert
{
//dummy($options);
    $data = loadEvents(NULL,NULL,$ev_id,NULL,$options);
    //dummy($options);
    if(isset($data['0']))
    {



    echo '<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Info</a></li>
    <li role="presentation" ><a href="#allPerformers" aria-controls="allPerformers" role="tab" data-toggle="tab">All Performers</a></li>
    <li role="presentation"><a href="#galery" aria-controls="galery" role="tab" data-toggle="tab">Galerie</a></li>';
   if($user_type == 'admin') echo '<li role="presentation"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Detalii</a></li>' ;
  echo '</ul>';
    echo '<!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="info">';

    //if($action == 'admin')
    showEvents($data,$options['show'],$options['poster'],$options['own']);
    //else
    //showEvents($data,'panel',$options['poster'],$options['own']);
    if(!$options['details'])
    {
    echo '<br/>';
    $details = variables_recursive_vname('event_details');
    echo '<div class ="col-md-12">';
    showDetails($details,'event_details','id_ev',$ev_id,array("visible"=>$options['visible']));
    echo '</div>';
    }

    echo '</div>';

    echo '<div role="tabpanel" class="tab-pane" id="allPerformers">';
      echo '<div>';


     $events_line = array();
    //$orchestra = variables_recursive_id(3);
   // dummy($orchestra);
    loadAllEventsPerson($ev_id,NULL,$person_type,$events_line);
    showEventPerson($events_line['events_person'],0,array(),array(),array());



        echo '</div>';
    //allPerformers DE SCHIMBAT FUNCTIA
    echo '</div>';

    /*
     * Aranjare text, sa fie frumos.
     * Si acum galeria
     */

    // Initialize the gallery array
    echo '<div role="tabpanel" class="tab-pane" id="galery">';
    echo '<div class="progress progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 40%"><span style="text-align:center">Loading and processing images. Please wait ...</span></div>';

    ?>
    <script>
$(document).ready(function(){

    $.ajaxSetup({
    beforeSend:function(){
        // show gif here, eg:
        $("#pleaseWaitDialog").show();
    },
    complete:function(){
        // hide gif here, eg:
        $("#pleaseWaitDialog").hide();
    }
        });

    $('.nav-tabs a[href="#galery"]').on('show.bs.tab', function (e) {
    $( "#galery" ).load( "event_tab_galery.php", { galery: "<?php echo $data[0]['galerie']?>" } );

     });

     $('.nav-tabs a[href="#galery"]').on('shown.bs.tab', function (e) {


        });


});
</script>



    <?php


   // if(isset($data[0]['galerie']) && $data[0]['galerie'] != NULL)
   // {
   // include_once('./UberGallery/resources/UberGallery.php');
    //$gallery = new UberGallery();

  // $gallery = UberGallery::init()->createGallery("../".$data[0]['galerie']);
//}
        echo "</div>"; //galerie





    if($user_type == 'admin')
    {
        echo '<div role="tabpanel" class="tab-pane" id="details">';
        sql_to_table($db, "SELECT * from event_details where id_ev=".$ev_id);

        echo '</div>';
    }


	  echo '</div>'; //div tab-content
      echo '</div>'; //div mare


}  //isset $data

}



    if($user_type=='admin')
    {
        switch($action)
        {
            case 'admin': include('event.php'); break;
            case 'set_person':  include('event_set_person.php'); break;

            case 'set_details':
            $id_ = $ev_id; // $id_, $ev_id; $per_id
            $_id_name = 'id_ev';
            $table_detail = 'event_details';
            $table_detail_id = 'eidd';
            $table_detail_idParent = 'eidd_parent';
            $variable_type = 'event_details';

            $mode = 'event';
            $mode_id = 'ev_id'; //ev_id, per_id

            include_once('set_details.php');
            break;

            case 'set_repertory':  include('event_set_repertory.php'); break;
            case 'set_per2rep':



            include('event_set_person2repertory.php'); break;
        }
    }
     //if user_type = admin

     if($user_type=='editor')
    {
        switch($action)
        {

            case 'set_details':
            $id_ = $ev_id; // $id_, $ev_id; $per_id
            $_id_name = 'id_ev';
            $table_detail = 'event_details';
            $table_detail_id = 'eidd';
            $table_detail_idParent = 'eidd_parent';
            $variable_type = 'event_details';

            $mode = 'event';
            $mode_id = 'ev_id'; //ev_id, per_id

            include_once('set_details.php');
            break;

        }
    }




} // // if action view // admin



?>



