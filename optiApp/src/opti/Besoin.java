package opti;

import java.util.ArrayList;
import java.util.Date;

public class Besoin {
    private int id;
    private Client client;
    private Date date;
    private String type;
    private ArrayList<Competence> competencesRequises;

    public Besoin(int id, Date date, String type, Client client, ArrayList<Competence> competencesRequises) {
        this.id = id;
        this.date = date;
        this.type = type;
        this.competencesRequises = competencesRequises;
    }

    public Besoin(int id, Date date, String type, ArrayList<Competence> competencesRequises) {
        this.id = id;
        this.date = date;
        this.type = type;
        this.competencesRequises = competencesRequises;
    }

    public int getId() {
        return id;
    }

    public ArrayList<Competence> getCompetencesRequises() {
        return competencesRequises;
    }
}