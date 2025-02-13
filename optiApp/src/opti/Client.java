package opti;

import java.util.ArrayList;

public class Client {
    private String id;
    private String nom;
    private ArrayList<Besoin> besoins;

    public Client(String id, String nom) {
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

    public String getId() {
        return id;
    }
}