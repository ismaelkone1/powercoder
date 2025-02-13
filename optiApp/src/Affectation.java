class Affectation {
    private Salarie salarie;
    private Besoin besoin;
    private int score;

    public Affectation(Salarie salarie, Besoin besoin, int score) {
        this.salarie = salarie;
        this.besoin = besoin;
        this.score = score;
    }

    public int getScore() {
        return score;
    }
}