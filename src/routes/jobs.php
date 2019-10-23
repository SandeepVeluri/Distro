<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = new \Slim\App;

//Get all jobs
$app->get('/api/jobs', function(Request $request, Response $response ){
    $sql = "SELECT *FROM jobs";
    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $jobs = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($jobs);
    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});
 

//get a single job
$app->get('/api/jobs/{id}', function(Request $request, Response $response ){
    $id = $request->getAttribute('id');
    $sql = "SELECT *FROM jobs WHERE id = $id";
    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $job = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($job);
    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});


//Add a job
$app->post('/api/jobs/add', function(Request $request, Response $response ){
    $job_name = $request->getParam('job_name');
    $client_name = $request->getParam('client_name');
    $node_name = $request->getParam('node_name');
   
    $sql = "INSERT INTO jobs (job_name,client_name,node_name) VALUES
    (:job_name,:client_name,:node_name)";
    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':job_name',$job_name);
        $stmt->bindParam(':client_name',$client_name);
        $stmt->bindParam(':node_name',$node_name);

        $stmt->execute();
      
        echo '{"notice": {"text": "Job added"}}';
       
    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});

//Update a job
$app->post('/api/jobs/update/{id}', function(Request $request, Response $response ){
    
    $id = $request->getAttribute('id');
    
    $job_name = $request->getParam('job_name');
    $client_name = $request->getParam('client_name');
    $node_name = $request->getParam('node_name');
   
    $sql = "UPDATE jobs SET
                job_name = :job_name,
                client_name = :client_name,
                node_name = :node_name
            WHERE id = $id";


    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':job_name',$job_name);
        $stmt->bindParam(':client_name',$client_name);
        $stmt->bindParam(':node_name',$node_name);

        $stmt->execute();
      
        echo '{"notice": {"text": "Job updated"}}';
       
    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});


//delete a single job
$app->post('/api/jobs/delete', function(Request $request, Response $response ){
    
    $id = $request->getParam('id');

    $sql = "DELETE *FROM jobs WHERE id = :id";
    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',$id);
        
        $stmt->execute();

        echo '{"notice": {"text": "Job deleted"}}';

    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});

//add file uri
$app->post('/api/jobs/adduri', function(Request $request, Response $response ){
    $job_name = $request->getParam('job_name');
    $client_name = $request->getParam('client_name');
    $node_name = $request->getParam('node_name');
    $uri = $request->getParam('uri');
    $file_name = $request->getParam('file_name'); 
    $file = $request->getParam('file');
   
    echo $file;
    $target_dir = "../src/routes/uploads/";
    $target_file = $target_dir . basename($_FILES[$file]["name"]);
    $uploadOk = 1;

     //Check if the file already exists
     if(file_exists($target_file)){
        echo "Sorry, file already exists";
        $uploadOk = 0;
    }

    //Check if file size greater than 1 MB
    if($_FILES[$file]["size"] > 1000000){
        echo "Sorry, file too large";
        echo "Please upload files smaller than 1 MB";
        $uploadOk = 0;
    }

    //Check if $uploadOk = 0 by an error
    if($uploadOk == 0){
        echo "Sorry, your file was not uploaded";
    } else {
        if(move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)){
            echo "The file" . basename($_FILES[$file]["name"]). " has been uploaded";
        } else {
            echo "Sorry, there was an error in uploading your file";
        }
    }
    
    $sql = "INSERT INTO jobs (job_name,client_name,node_name,uri,file_name) VALUES
    (:job_name,:client_name,:node_name,:uri,:file_name)";
    try{
        //GET DB object
        $db = new db();
        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':job_name',$job_name);
        $stmt->bindParam(':client_name',$client_name);
        $stmt->bindParam(':node_name',$node_name);
        $stmt->bindParam(':uri',$uri);
        $stmt->bindParam(':file_name',$file_name);

        $stmt->execute();
      
        echo '{"notice": {"text": "Job added"}}';
       
    }catch(PDOException $e){
        echo'{"error": {"text": '.$e->getMessage().'}';
    }
});