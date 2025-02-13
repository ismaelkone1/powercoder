package java;

import java.util.ArrayList;

public class Client {
    private int id;
    private String nom;
    private ArrayList<Besoin> besoins;

    public Client(int id, String nom) {
        this.id = id;
        this.nom = nom;
        this.besoins = new ArrayList<>();
    }

    public void ajouterBesoin(Besoin besoin) {
        besoins.add(besoin);
    }

    public ArrayList<Besoin> getBesoins() {
        return besoins;
    }
}