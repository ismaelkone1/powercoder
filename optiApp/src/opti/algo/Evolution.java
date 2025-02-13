package opti.algo;

import opti.Affectation;
import opti.Besoin;
import opti.Client;
import opti.Salarie;
import opti.bd.DatabaseConnection;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Random;

public class Evolution {
    //La liste des meilleures affectations
    private ArrayList<ArrayList<Affectation>> meilleuresAffectations = new ArrayList<>();

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

        for (int i = 0; i < moitie; i++) {
            meilleuresAffectations.add(affectationsTriee.get(i).getKey());
        }

        // Générer de nouvelles affectations via croisement
        ArrayList<ArrayList<Affectation>> enfants = new ArrayList<>();
        Random random = new Random();
        for (int i = 0; i < taille - moitie; i++) {
            // Sélectionner deux parents aléatoires parmi les meilleurs
            ArrayList<Affectation> parent1 = meilleuresAffectations.get(random.nextInt(meilleuresAffectations.size()));
            ArrayList<Affectation> parent2 = meilleuresAffectations.get(random.nextInt(meilleuresAffectations.size()));

            // Créer un enfant en croisant les parents
            ArrayList<Affectation> enfant = croiserAffectations(parent1, parent2);
            enfants.add(enfant);
        }

        // Combiner les meilleures affectations et les nouvelles affectations
        ArrayList<ArrayList<Affectation>> nouvellePopulation = new ArrayList<>();
        nouvellePopulation.addAll(meilleuresAffectations);
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
            while (enfant.contains(affectation) && meilleuresAffectations.contains(affectation)) {
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

    private static ArrayList<Affectation> genererAffectationsAleatoires() {
        ArrayList<Affectation> affectations = new ArrayList<>();

        //On récupère les salariés
        HashSet<Salarie> salaries = DatabaseConnection.getTousLesSalaries();
        //On récupère les besoins
        HashSet<Besoin> besoins = DatabaseConnection.getTousLesBesoins();
        //ON récupère les clients
        HashSet<Client> clients = DatabaseConnection.getTousLesClients();

        //On génère les affectations en fonction du nombre de besoins
        for (int i = 0; i < besoins.size(); i++) {
            Salarie salarie = (Salarie) salaries.toArray()[(int) (Math.random() * salaries.size())];
            Besoin besoin = (Besoin) besoins.toArray()[(int) (Math.random() * besoins.size())];
            Client client = (Client) clients.toArray()[(int) (Math.random() * clients.size())];

            Affectation affectation = new Affectation(salarie, besoin, 0, client);
            affectations.add(affectation);
        }

        return affectations;
    }

    public static ArrayList<Affectation> lancerEvolution(int iterations) {
        // Initialisation avec une liste d'affectations
        ArrayList<ArrayList<Affectation>> affectations = new ArrayList<>();
        for (int i = 0; i < 15; i++) {
            ArrayList<Affectation> affectation = genererAffectationsAleatoires();
            //On ajoute l'affectation à la liste si elle n'est pas déjà présente
            while (affectations.contains(affectation)) {
                affectation = genererAffectationsAleatoires();
            }
            affectations.add(affectation);
            //affectations.add(genererAffectationsAleatoires());
        }

        Evolution evolution = new Evolution();

        for (int iteration = 0; iteration < iterations; iteration++) {
            System.out.println("Iteration " + iteration);

            // Évaluation des affectations
            HashMap<ArrayList<Affectation>, Double> stats = new HashMap<>();
            for (ArrayList<Affectation> affectation : affectations) {
                stats.put(affectation, 0.0);
            }
            stats = evolution.evaluerAffectations(stats);

            // Affichage des scores
            for (ArrayList<Affectation> affectation : stats.keySet()) {
                System.out.println("Affectation : " + affectation + " Score : " + stats.get(affectation));
            }

            // Appliquer l'évolution
            affectations = evolution.evoluer(stats);
        }

        // Retourner la meilleure affectation trouvée
        return affectations.get(0);
    }
}

