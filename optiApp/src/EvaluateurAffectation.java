import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;

public class EvaluateurAffectation {

    public static HashMap<ArrayList<Affectation>, Integer> evaluerAffectations(HashMap<ArrayList<Affectation>, Integer> affectationsMap) {
        HashMap<ArrayList<Affectation>, Integer> scoresMap = new HashMap<>();

        for (ArrayList<Affectation> affectations : affectationsMap.keySet()) {
            int score = calculerScore(affectations);
            scoresMap.put(affectations, score);
        }

        return scoresMap;
    }

    public static int calculerScore(ArrayList<Affectation> affectations) {
        int scoreTotal = 0;

        // Stocker les besoins réalisés pour chaque client
        HashMap<Client, Integer> besoinsRealisesParClient = new HashMap<>();

        // Stocker les salariés affectés pour détecter les inactifs
        HashSet<Salarie> salariesAffectes = new HashSet<>();

        // Stocker les clients ayant des besoins réalisés
        HashSet<Client> clientsAvoirBesoin = new HashSet<>();

        for (Affectation affectation : affectations) {
            // Calculer le score de l'affectation
            int points = affectation.calculerScore(besoinsRealisesParClient, salariesAffectes, clientsAvoirBesoin);

            // Ajouter le score de l'affectation au score total
            scoreTotal += points;

            // Comptabiliser le besoin réalisé pour ce client
            Client client = affectation.getClient();
            int besoinsRealises = besoinsRealisesParClient.getOrDefault(client, 0);
            besoinsRealisesParClient.put(client, besoinsRealises + 1);
        }

        // Appliquer les malus pour les clients sans besoin affecté (Règle 6)
        for (Client client : besoinsRealisesParClient.keySet()) {
            if (besoinsRealisesParClient.get(client) == 0) {
                scoreTotal -= 10;
            }
        }

        // Appliquer les malus pour les salariés inactifs (Règle 7)
        HashSet<Salarie> tousLesSalaries = getTousLesSalaries();
        for (Salarie salarie : tousLesSalaries) {
            if (!salariesAffectes.contains(salarie)) {
                scoreTotal -= 10;
            }
        }

        return scoreTotal;
    }

    //TODO : Méthode pour obtenir tous les salariés
    private static HashSet<Salarie> getTousLesSalaries() {
        return new HashSet<>();
    }
}
