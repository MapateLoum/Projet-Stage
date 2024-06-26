<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Papaloum1613";
$dbname = "voyages";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$type_vol = $_POST['type-vol'] ?? '';
$nombre_voyageurs = $_POST['nombre-voyageurs'] ?? 1;
$classe = $_POST['classe'] ?? '';
$depart = $_POST['depart'] ?? '';
$arrivee = $_POST['arrivee'] ?? '';
$date_depart = $_POST['date-depart'] ?? '';
$date_retour = $_POST['date-retour'] ?? '';

// Requête SQL pour récupérer les vols
$sql = "SELECT * FROM vols WHERE depart = '$depart' AND arrivee = '$arrivee' AND date_depart = '$date_depart'";
if ($type_vol == "aller-retour") {
    $sql .= " AND date_retour = '$date_retour'";
}

$result = $conn->query($sql);

// Afficher les résultats
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Résultats de la recherche de vols</title>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <a class="flex items-center" href="./index.html">
                <img id="logo" src="./images/logo.jpg" alt="logo" width="30" height="30">
                <span class="ml-2 font-semibold text-lg">Logo</span>
            </a>
        </nav>
    </header>

    <main class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Résultats de la recherche de vols</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="grid grid-cols-1 gap-4">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="flex items-center bg-white p-4 shadow rounded">
                        <!-- Logo de la compagnie aérienne -->
                        <div class="flex-shrink-0 mr-4">
                            <?php
                            // Conversion des données binaires en Base64
                            $imageData = base64_encode($row['image']);
                            // Formatage de l'URL de l'image
                            $imageSrc = 'data:image/png;base64,'.$imageData;
                            ?>
                            <img src="<?php echo $imageSrc; ?>" alt="Logo de la compagnie" class="h-8">
                        </div>
                        <!-- Détails du vol -->
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-bold"><?php echo $row["compagnie"]; ?></p>
                                    <p><?php echo $row["depart"]; ?> - <?php echo $row["arrivee"]; ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold"><?php echo $row["prix"]; ?> FCFA / personne</p>
                                    <p class="text-sm"><?php echo $row["prix"] * $nombre_voyageurs; ?> au total</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p><strong>Départ:</strong> <?php echo $row["heure_depart"]; ?></p>
                                    <p><strong>Arrivée:</strong> <?php echo $row["heure_arrivee"]; ?></p>
                                </div>
                                <div class="text-right">
                                    <p><strong>Durée:</strong> <?php echo $row["duree_vol"]; ?></p>
                                    <p><strong>Escale:</strong> <?php echo $row["escale"]; ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Bouton Voir l'offre -->
                        <div class="ml-4">
                            <a href="details_vol.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white py-2 px-4 rounded">Voir l'offre</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-red-500">Aucun vol trouvé pour les critères de recherche spécifiés.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
