package java;

public class Affectation {
    private Salarie salarie;
    private Besoin besoin;
    private double score;

    public Affectation(Salarie salarie, Besoin besoin, double score) {
        this.salarie = salarie;
        this.besoin = besoin;
        this.score = score;
    }

    public double getScore() {
        return score;
    }
}