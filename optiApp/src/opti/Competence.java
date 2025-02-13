package opti;

import java.util.Objects;

public class Competence {
    private int id;
    private String type;

    public Competence(int id, String type) {
        this.type = type;
        this.id = id;
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj) return true;
        if (obj == null || getClass() != obj.getClass()) return false;
        Competence that = (Competence) obj;
        return Objects.equals(type, that.type); // Compare uniquement le type
    }

    @Override
    public int hashCode() {
        return Objects.hash(type);
    }


    public String getType() {
        return type;
    }

    public int getId() {
        return id;
    }
}