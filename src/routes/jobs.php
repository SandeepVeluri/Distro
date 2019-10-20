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
$app->put('/api/jobs/update/{id}', function(Request $request, Response $response ){
    
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
