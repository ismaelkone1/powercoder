package opti.algo;
import opti.Affectation;

import opti.Affectation;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class MainEvaluation {
    public static void main(String[] args) {
        // Fichier de sortie pour les logs
        String fileName = "Ressources/evolution_affectations.csv";

        try (FileWriter writer = new FileWriter(fileName)) {
            writer.write("Iteration;Meilleur Score;Moyenne Score\n");

            // Initialisation avec une liste d'affectations
            ArrayList<ArrayList<Affectation>> affectations = new ArrayList<>();
            for (int i = 0; i < 40; i++) { // 40 solutions initiales
                affectations.add(genererAffectationsAleatoires());
            }

            for (int iteration = 0; iteration < 100; iteration++) {
                System.out.println("Iteration " + iteration);

                // Évaluation des affectations
                HashMap<ArrayList<Affectation>, Double> stats = new HashMap<>();
                for (ArrayList<Affectation> affectation : affectations) {
                    stats.put(affectation, 0.0);
                }

                Evolution evolution = new Evolution();
                //stats = evolution.evoluer(stats);
                affectations = evolution.evoluer(stats);

                // Log des stats
                //logStats(writer, iteration + 1, stats);
            }
            System.out.println("Fin de la simulation. Données enregistrées dans " + fileName);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private static void logStats(FileWriter writer, int iteration, HashMap<List<Affectation>, Integer> stats) throws IOException {
        int meilleurScore = stats.values().stream().max(Integer::compareTo).orElse(0);
        double moyenneScore = stats.values().stream().mapToInt(Integer::intValue).average().orElse(0.0);
        writer.write(iteration + ";" + meilleurScore + ";" + moyenneScore + "\n");
    }

    private static ArrayList<Affectation> genererAffectationsAleatoires() {
        // Générer une affectation aléatoire selon tes contraintes
        return new ArrayList<>(); // À implémenter
    }
}
