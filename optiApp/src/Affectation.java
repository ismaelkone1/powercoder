import java.util.HashMap;
import java.util.Set;

class Affectation {
    private Salarie salarie;
    private Client client;
    private Besoin besoin;
    private double score;

    public Affectation(Salarie salarie, Besoin besoin, double score) {
        this.salarie = salarie;
        this.besoin = besoin;
        this.score = score;
        this.client = client;
    }

    public int calculerScore(HashMap<Client, Integer> besoinsRealisesParClient, Set<Salarie> salariesAffectes, Set<Client> clientsAvoirBesoin) {
        // Vérification de la compétence requise (Règle 3)
        if (!salarie.getCompetences().containsKey(besoin.getCompetencesRequises().get(0))) {
            System.out.println("Affectation invalide : " + salarie + " ne possède pas la compétence requise pour " + besoin);
            return 0; // Affectation invalide
        }

        // Ajout du score basé sur l'intérêt du salarié (Règle 4)
        int points = salarie.getInteret(besoin.getCompetencesRequises().get(0));

        // Appliquer le malus dégressif pour les besoins du même client (Règle 5)
        int besoinsRealises = besoinsRealisesParClient.getOrDefault(client, 0);
        if (besoinsRealises > 1) {
            points -= besoinsRealises - 1; // Applique un malus dégressif
        }
        points = Math.max(1, points); // Assurer que le score ne soit pas inférieur à 1

        // Mettre à jour les clients ayant au moins un besoin réalisé
        clientsAvoirBesoin.add(client);

        // Marquer le salarié comme actif
        salariesAffectes.add(salarie);

        return points;
    }


    public double getScore() {
        return score;
    }

    public Salarie getSalarie() {
        return salarie;
    }

    public Besoin getBesoin() {
        return besoin;
    }

    public Client getClient() {
        return client;
    }

    public String toString() {
        return  salarie + " affecté au besoin " + besoin + " avec un score de " + score;
    }
}