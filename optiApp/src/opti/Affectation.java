package opti;

import java.util.HashMap;
import java.util.HashSet;

public class Affectation {
    private Salarie salarie;
    private Besoin besoin;
    private double score;
    private Client client;

    public Affectation(Salarie salarie, Besoin besoin, double score, Client client) {
        this.salarie = salarie;
        this.besoin = besoin;
        this.score = score;
        this.client = client;
    }

    public void calculerScore(HashMap<Client, Integer> besoinsRealisesParClient, HashSet<Salarie> salariesAffectes, HashSet<Client> clientsAvoirBesoin) {
        System.out.println("Salarié : "+this.salarie);
        System.out.println("Besoin : "+this.besoin);
        System.out.println("Détails du besoin : "+this.besoin.getCompetencesRequises());

        System.out.println("Client : "+this.client);

        // Vérification de la compétence requise (Règle 3)
        //Affichage des compétences du salarié
        System.out.println("Compétences du salarié BITCH: ");
        for (Competence competence : this.salarie.getCompetences().keySet()) {
            System.out.println("Compétence : "+competence.getType()+" Intérêt : "+this.salarie.getInteret(competence));
        }

        //Affichage des compétences requises pour le besoin
        System.out.println("Compétences requises pour le besoin : ");
        for (Competence competence : this.besoin.getCompetencesRequises()) {
            System.out.println("Compétence requise : "+competence.getType());
        }

        //parcours toutes les compétences requises pour le besoin
        for (Competence competence : this.besoin.getCompetencesRequises()) {
            if (!this.salarie.getCompetences().containsKey(competence)) {
                System.out.println("Affectation invalide : " + this.salarie + " ne possède pas la compétence requise pour " + this.besoin);
                this.score = 0;
                return;
            }
        }

        // Ajout du score basé sur l'intérêt du salarié (Règle 4)
        double points = salarie.getInteret(besoin.getCompetencesRequises().get(0));

        // Appliquer le malus dégressif pour les besoins du même client (Règle 5)
        double besoinsRealises = besoinsRealisesParClient.getOrDefault(client, 0);
        if (besoinsRealises > 1) {
            points -= besoinsRealises - 1; // Applique un malus dégressif
        }
        points = Math.max(1, points); // Assurer que le score ne soit pas inférieur à 1

        // Mettre à jour les clients ayant au moins un besoin réalisé
        clientsAvoirBesoin.add(client);

        // Marquer le salarié comme actif
        salariesAffectes.add(salarie);

        this.score = points;
        //return points;
    }


    public double getScore() {
        return score;
    }

    public Client getClient() {
        return client;
    }

    public Salarie getSalarie() {
        return salarie;
    }

    public Besoin getBesoin() {
        return besoin;
    }
}