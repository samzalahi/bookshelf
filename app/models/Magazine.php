<?php

class Magazine
{
    private $db;
    public function __construct()
    {
        // Init database class
        $this->db = new Database;
    }

    public function add($data)
    {
        // Init query
        // die(var_dump($data));
        $this->db->query('INSERT INTO magazines (mag_no, title, issue_no, year, date, publication, container, description, status, file_type, cover_img_type, created_by, created_at) 
        VALUES (:mag_no, :title, :issue_no, :year, :date, :publication, :container, :description, :status, :file_type, :cover_img_type, :created_by, NOW())');
        
        // Bind values
        $this->db->bind(':mag_no', $data['mag_no']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':issue_no', $data['issue_no']);
        $this->db->bind(':year', $data['year']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':publication', $data['publication']);
        $this->db->bind(':container', $data['container']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['availability']);
        $this->db->bind(':file_type', $data['file_type']);
        $this->db->bind(':cover_img_type', $data['cover_img_type']);
        $this->db->bind(':created_by', $_SESSION['user_id']);

        // Execute query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        // Init query
        // die(var_dump($data));
        $this->db->query('UPDATE magazines SET mag_no = :mag_no, title = :title, issue_no = :issue_no, year = :year, date = :date, publication = :publication, container = :container, description = :description, status = :status, file_type = ifnull(:file_type,file_type), cover_img_type = ifnull(:cover_img_type,cover_img_type), updated_by = :updated_by, updated_at = NOW()
        WHERE id = :id');

        // Bind values
        $this->db->bind(':mag_no', $data['mag_no']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':issue_no', $data['issue_no']);
        $this->db->bind(':year', $data['year']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':publication', $data['publication']);
        if (empty($data['file_type'])) {
            $this->db->bind(':file_type', null);
        } else {
            $this->db->bind(':file_type', $data['file_type']);
        }
        if (empty($data['cover_img_type'])) {
            $this->db->bind(':cover_img_type', null);
        } else {
            $this->db->bind(':cover_img_type', $data['cover_img_type']);
        }
        $this->db->bind(':status', $data['availability']);
        if ($data['availability'] == true) {
            $this->db->bind(':container', $data['container']);
        } else {
            $this->db->bind(':container', null);
        }
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':updated_by', $_SESSION['user_id']);
        $this->db->bind(':id', $data['id']);

        // Execute query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function import($data, $no_of_rows)
    {
        // die(var_dump($data[1]['C']));
        // die(var_dump($nr));
        try {
            // Start time
            // $time_start = microtime(true);

            // Start transaction
            $this->db->beginTransaction();
            // Init query
            $this->db->query('INSERT INTO magazines (mag_no, title, issue_no, publication, container, tmp_no, created_by, created_at)
            SELECT :mag_no, :title, :issue_no, :publication, :container, :tmp_no, :created_by, now()
            FROM DUAL
            WHERE NOT EXISTS (SELECT * FROM magazines WHERE tmp_no = :tmp_no)  
            LIMIT 1');

            for ($i=2; $i<=$no_of_rows; $i++) {
                // Bind values
                $this->db->bind(':mag_no', $data[$i]['B']);
                $this->db->bind(':title', $data[$i]['B']);
                $this->db->bind(':issue_no', $data[$i]['C']);
                $this->db->bind(':publication', $data[$i]['D']);
                $this->db->bind(':container', $data[$i]['E']);
                $this->db->bind(':tmp_no', $data[$i]['A']);
                $this->db->bind(':created_by', $_SESSION['user_id']);

                // Execute query
                // if (!$this->db->execute()) {
                //     return false;
                // }
                $this->db->execute();
            }
            // Commit data
            if ($this->db->commitTransaction()) {
                return true;
            } else {
                return false;
            }

            // Calculate time
            // $time_end = microtime(true);
            // $time = $time_end - $time_start;
            // echo "Completed in ". $time ." seconds <hr>";
        } catch (Exception $e) {
            $this->db->cancelTransaction(); // rollback data if there is an error
            // echo "Failed: " . $e->getMessage(); // this is wrong
            throw new $e;
        }
    }

    public function addTag($tags, $mag_id)
    {
        // Loop tags to insert
        foreach ($tags as $tag) {
            // Init query
            $this->db->query('INSERT INTO tags (tag) VALUES (:tag) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
            // Bind values
            $this->db->bind(':tag', $tag);

            // Execute query
            $this->db->execute();

            // Get last tag id
            $last_tag_id = $this->db->lastId();

            $this->db->query('INSERT INTO mags_tag (mag_id, tag_id) VALUES (:mag_id, :tag_id)');

            // Add tag to magazines
            $this->db->bind(':mag_id', $mag_id);
            $this->db->bind(':tag_id', $last_tag_id);

            // Execute query
            $this->db->execute();
        }
    }

    public function updateTag($tags, $mag_id)
    {
        // Loop tags to insert
        foreach ($tags as $tag) {
            // Init query
            $this->db->query('INSERT INTO tags (tag) VALUES (:tag) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
            // Bind values
            $this->db->bind(':tag', $tag);

            // Execute query
            $this->db->execute();

            // Get last tag id
            $last_tag_id = $this->db->lastId();

            $this->db->query('INSERT INTO mags_tag (mag_id, tag_id) VALUES (:mag_id, :tag_id)');

            // Add tag to magazine
            $this->db->bind(':mag_id', $mag_id);
            $this->db->bind(':tag_id', $last_tag_id);

            // Execute query
            $this->db->execute();
        }
    }

    public function getAllMagazines()
    {
        // Init query
        $this->db->query('SELECT id, mag_no, title, issue_no, year, date, publication, container, description, status, file_type, cover_img_type, created_by, created_at, updated_by, updated_at
        FROM magazines
        LIMIT 10');

        // Execute query
        $results = $this->db->resultSet();

        return $results;
    }

    public function getMagazinesByKey($key)
    {
        // Init query
        $this->db->query('SELECT id, mag_no, title, issue_no, year, date, publication, container, description, status, file_type, cover_img_type, created_by, created_at, updated_by, updated_at
        FROM magazines
        WHERE mag_no LIKE :key OR title LIKE :key OR issue_no LIKE :key');
        

        // Bind value
        $this->db->bind(':key', "$key%");

        // Get results from db
        $resutls = $this->db->resultSet();

        return $resutls;
    }

    public function getMagazinesByTag($key)
    {
        $keys = "'". implode("', '", explode(',', $key)) . "'";
        // echo $keys;
        // $keys = preg_replace('/\s+/', '', $keys);
        $keys = preg_replace('/\s+?\'\s+?/', '\'', $keys);
        // echo $keys;
        // Init query
        $this->db->query('SELECT DISTINCT m.id, m.mag_no, m.title, m.issue_no, m.year, m.date, m.publication, m.container, m.description, m.status, m.file_type, m.cover_img_type, m.created_by, m.created_at, m.updated_by, m.updated_at,
        (SELECT count(*) FROM mags_tag WHERE mt.mag_id = t.id) AS total_magazines,
        (SELECT count(*) FROM mags_tag WHERE mt.tag_id = t.id AND m.status = 1) AS total_available_magazines
        FROM magazines m
        JOIN mags_tag mt ON mt.mag_id = m.id
        JOIN tags t ON mt.tag_id = t.id
        WHERE t.tag IN ('.$keys.')');

        // Get results from db
        $resutls = $this->db->resultSet();

        return $resutls;
    }

    public function getMagazineById($id)
    {
        // Init query
        $this->db->query('SELECT id, mag_no, title, issue_no, year, date, publication, container, description, status, file_type, cover_img_type, created_by, created_at, updated_by, updated_at
        FROM magazines 
        WHERE id = :id ');

        // Bind value
        $this->db->bind(':id', $id);

        if ($row = $this->db->single()) {
            return $row;
        }
    }

    public function getMagazinesByMagNo($mag_no)
    {
        // Init query
        $this->db->query('SELECT id, mag_no, title, issue_no, year, date, publication, container, description, status, file_type, cover_img_type, created_by, created_at, updated_by, updated_at
        FROM magazines 
        WHERE mag_no = :id');

        // Bind value
        $this->db->bind(':id', $mag_no);

        if ($results = $this->db->resultSet()) {
            return $results;
        } else {
            // die('Error: Could not retrive row from db');\
            return [];
        }
    }

    public function getLastId()
    {
        return $this->db->lastId();
    }

    public function findMagazineById($id)
    {
        // Init query
        $this->db->query('SELECT magazines.id, magazines.mag_no, magazines.title, magazines.issue_no, magazines.year,magazines.date, magazines.publication, magazines.container, magazines.description, magazines.status, magazines.file_type, magazines.cover_img_type, magazines.created_by, magazines.created_at, magazines.updated_by, magazines.updated_at, users.name
        FROM magazines
        JOIN users ON 
        magazines.created_by = users.id
        WHERE magazines.id = :id');

        // Bind value
        $this->db->bind(':id', $id);
        
        // Get Result from db
        $data = $this->db->single();

        return $data;
    }

    public function findMagByMagNo($mag_no)
    {
        // Init query
        $this->db->query('SELECT mag_no FROM magazines WHERE mag_no = :mag_no');

        // Bind value
        $this->db->bind(':mag_no', $mag_no);

        // Execute query
        $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        // Init query
        $this->db->query('DELETE FROM magazines WHERE id = :id');

        // Bind value
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTag($id)
    {
        // Init query
        $this->db->query('DELETE FROM mags_tag WHERE mag_id = :id');

        // Bind value
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
