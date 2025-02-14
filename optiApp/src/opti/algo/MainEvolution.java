package opti.algo;
import opti.*;

import opti.Affectation;
import opti.bd.DatabaseConnection;

import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;

public class MainEvolution {
    public static void main(String[] args) {
        // Fichier de sortie pour les logs
        //String fileName = "Ressources/evolution_affectations.csv";
        //On lance la bd
        DatabaseConnection.main(args);

        // Initialisation avec une liste d'affectations
        ArrayList<ArrayList<Affectation>> affectations = new ArrayList<>();
        //On génère 40 listes d'affectations aléatoires
        for (int i = 0; i < 15; i++) {
            affectations.add(genererAffectationsAleatoires());
        }

        System.out.println("Les affectations initiales sont :");
        for (ArrayList<Affectation> affectation : affectations) {
            System.out.println(affectation);
        }

        for (int iteration = 0; iteration < 15; iteration++) {
            System.out.println("Iteration " + iteration);

            // Évaluation des affectations
            HashMap<ArrayList<Affectation>, Double> stats = new HashMap<>();
            for (ArrayList<Affectation> affectation : affectations) {
                stats.put(affectation, 0.0);
            }

            Evolution evolution = new Evolution();
            //évaluation des affectations
            stats = evolution.evaluerAffectations(stats);
            //Détail de l'évaluation
            for (ArrayList<Affectation> affectation : stats.keySet()) {
                System.out.println("Affectation : " + affectation + " Score : " + stats.get(affectation));
            }


            //stats = evolution.evoluer(stats);
            affectations = evolution.evoluer(stats);

            //On affiche les affectations
            for (ArrayList<Affectation> affectation : affectations) {
                System.out.println(affectation);
            }

            // Log des stats
            //logStats(writer, iteration + 1, stats);
        }
        System.out.println("Fin de la simulation");
        System.out.println("Affichage de la meilleure affectation :");
        System.out.println(affectations.get(0).get(0));

        //On affiche le détail de la meilleure affectation
        for (Affectation affectation : affectations.get(0)) {
            System.out.println("Salarié : "+affectation.getSalarie());
            System.out.println("Compétences du salarié : ");
            for (Competence competence : affectation.getSalarie().getCompetences().keySet()) {
                System.out.println("Compétence : "+competence.getType()+" Intérêt : "+affectation.getSalarie().getInteret(competence));
            }

            System.out.println("Besoin : "+affectation.getBesoin());
            System.out.println("Compétences requises pour le besoin : "+affectation.getBesoin().getCompetencesRequises().get(0).getType());

            //Affichage des salariés
        }

    }

    private static void logStats(FileWriter writer, int iteration, HashMap<List<Affectation>, Integer> stats) throws IOException {
        int meilleurScore = stats.values().stream().max(Integer::compareTo).orElse(0);
        double moyenneScore = stats.values().stream().mapToInt(Integer::intValue).average().orElse(0.0);
        writer.write(iteration + ";" + meilleurScore + ";" + moyenneScore + "\n");
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

}