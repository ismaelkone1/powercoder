package opti;

import java.util.HashMap;

public class Salarie {
    private int id;
    private String nom;
    private HashMap<Competence, Integer> competences;

    public Salarie(int id, String nom) {
        this.id = id;
        this.nom = nom;
        this.competences = new HashMap<>();
    }

    public void ajouterCompetence(Competence competence, int interet) {
        competences.put(competence, interet);
    }

    public int getInteret(Competence competence) {
        return competences.getOrDefault(competence, 0);
    }

    public HashMap<Competence, Integer> getCompetences() {
        return competences;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public int getId() {
        return id;
    }

    public String getNom() {
        return nom;
    }


}