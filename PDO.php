try
{
    $db = new PDO('mysql:localhost=ms8db;dbname=group25;charset=utf8', 'group25', '3uCTA8L2ID');
}
catch (Exception $e)
{
    die('Erreur!');
}