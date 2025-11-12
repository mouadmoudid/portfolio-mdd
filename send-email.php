<?php
// Configuration
$to_email = "mouadmoudid27@gmail.com";
$subject_prefix = "Portfolio Contact - ";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données du formulaire
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    
    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs correctement."]);
        exit;
    }
    
    // Construire le contenu de l'email
    $email_subject = $subject_prefix . $name;
    $email_body = "Vous avez reçu un nouveau message depuis votre portfolio.\n\n";
    $email_body .= "Nom: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message\n";
    
    // En-têtes de l'email
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Envoyer l'email
    if (mail($to_email, $email_subject, $email_body, $headers)) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Merci ! Votre message a été envoyé avec succès."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi du message. Veuillez réessayer."]);
    }
} else {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
}
?>

