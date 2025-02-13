package opti.algo;

import opti.Affectation;
import opti.Client;
import opti.Salarie;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Random;

public class Evolution {

    public static HashMap<ArrayList<Affectation>, Double> evaluerAffectations(HashMap<ArrayList<Affectation>, Double> affectationsMap) {
        HashMap<ArrayList<Affectation>, Double> scoresMap = new HashMap<>();

        for (ArrayList<Affectation> affectations : affectationsMap.keySet()) {
            double score = calculerScore(affectations);
            scoresMap.put(affectations, score);
        }

        return scoresMap;
    }

    public static double calculerScore(ArrayList<Affectation> affectations) {
        int scoreTotal = 0;

        // Stocker les besoins réalisés pour chaque client
        HashMap<Client, Integer> besoinsRealisesParClient = new HashMap<>();

        // Stocker les salariés affectés pour détecter les inactifs
        HashSet<Salarie> salariesAffectes = new HashSet<>();

        // Stocker les clients ayant des besoins réalisés
        HashSet<Client> clientsAvoirBesoin = new HashSet<>();

        for (Affectation affectation : affectations) {
            // Calculer le score de l'affectation
            affectation.calculerScore(besoinsRealisesParClient, salariesAffectes, clientsAvoirBesoin);

            double points = affectation.getScore();

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
                scoreTotal -= 10; // Malus de -10 pour les clients sans besoin affecté
            }
        }

        // Appliquer les malus pour les salariés inactifs (Règle 7)
        HashSet<Salarie> tousLesSalaries = getTousLesSalaries();
        for (Salarie salarie : tousLesSalaries) {
            if (!salariesAffectes.contains(salarie)) {
                scoreTotal -= 10; // Malus de -10 pour les salariés inactifs
            }
        }

        return scoreTotal;
    }

    // TODO : Méthode pour obtenir tous les salariés
    private static HashSet<Salarie> getTousLesSalaries() {
        return new HashSet<>();
    }

    public ArrayList<ArrayList<Affectation>> evoluer(HashMap<ArrayList<Affectation>, Double> affectationsMap) {
        // Trier les affectations par score décroissant
        ArrayList<HashMap.Entry<ArrayList<Affectation>, Double>> affectationsTriee = new ArrayList<>(affectationsMap.entrySet());
        affectationsTriee.sort((s1, s2) -> Double.compare(s2.getValue(), s1.getValue()));

        System.out.println("Meilleure solution : " + affectationsTriee.get(0).getKey());

        // Sélectionner la moitié des meilleures affectations
        int taille = affectationsTriee.size();
        int moitie = taille / 2;
        ArrayList<ArrayList<Affectation>> meilleures = new ArrayList<>();
        for (int i = 0; i < moitie; i++) {
            meilleures.add(affectationsTriee.get(i).getKey());
        }

        // Générer de nouvelles affectations via croisement
        ArrayList<ArrayList<Affectation>> enfants = new ArrayList<>();
        Random random = new Random();
        for (int i = 0; i < taille - moitie; i++) {
            // Sélectionner deux parents aléatoires parmi les meilleurs
            ArrayList<Affectation> parent1 = meilleures.get(random.nextInt(meilleures.size()));
            ArrayList<Affectation> parent2 = meilleures.get(random.nextInt(meilleures.size()));

            // Créer un enfant en croisant les parents
            ArrayList<Affectation> enfant = croiserAffectations(parent1, parent2);
            enfants.add(enfant);
        }

        // Combiner les meilleures affectations et les nouvelles affectations
        ArrayList<ArrayList<Affectation>> nouvellePopulation = new ArrayList<>();
        nouvellePopulation.addAll(meilleures);
        nouvellePopulation.addAll(enfants);

        return nouvellePopulation;
    }

    private ArrayList<Affectation> croiserAffectations(ArrayList<Affectation> parent1, ArrayList<Affectation> parent2) {
        ArrayList<Affectation> enfant = new ArrayList<>();
        Random random = new Random();
        for (int i = 0; i < parent1.size(); i++) {
            //on vérifie que l'enfant n'a pas déjà cette affectation
            Affectation affectation = randomChoice(
                    parent1.get(random.nextInt(parent1.size())),
                    parent2.get(random.nextInt(parent2.size()))
            );
            while (enfant.contains(affectation)) {
                affectation = randomChoice(
                        parent1.get(random.nextInt(parent1.size())),
                        parent2.get(random.nextInt(parent2.size()))
                );
            }
            enfant.add(affectation);
        }
        return enfant;
    }

    /**
     * Méthode pour choisir aléatoirement une propriété entre deux options.
     */
    private <T> T randomChoice(T option1, T option2) {
        return new Random().nextBoolean() ? option1 : option2;
    }
}

