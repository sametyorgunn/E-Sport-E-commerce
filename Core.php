<?php 
class Core{
    public function SessionTimeAndLoginControl(){
        $startedSession = $_SESSION["baslangic_zamani"] ?? null;
        $oturumSuresi = $_SESSION["sessionTime"] ?? 0;
        $userId = $_SESSION["userID"] ?? null;

        if ($startedSession && (time() - $startedSession > $oturumSuresi))
        {
            session_unset();
            session_destroy();
            header("Location: /Urun/Login");
            exit;
        }
        if (!empty($userId)) {
            return $userId;
        } 
        else
        {
            $userId = 0;
            return $userId;
            exit;
        }
    }
}

?>