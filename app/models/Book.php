<?php

class Book
{
    private $db;
    public function __construct()
    {
        // Init database class
        $this->db = new Database;
          // Mount shere folder
    }

    public function add($data)
    {
        // Init query
        // die(var_dump($data));
        $this->db->query('INSERT INTO books (book_no, title, author, edition, publication, no_of_books, container, description,   file_type, cover_img_type, status, copyright, tag, created_by, created_at) 
        VALUES (:book_no, :title, :author, :edition, :publication, :no_of_books, :container, :description, :file_type, :cover_img_type,:status, :copyright, :tag, :created_by, NOW())');
        
        // Bind values
        $this->db->bind(':book_no', $data['book_no']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':edition', $data['edition']);
        $this->db->bind(':publication', $data['publication']);
        $this->db->bind(':no_of_books', $data['no_of_books']);
        $this->db->bind(':container', $data['container']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':file_type', $data['file_type']);
        $this->db->bind(':cover_img_type', $data['cover_img_type']);
        $this->db->bind(':status', $data['availability']);
        $this->db->bind(':copyright', $data['copyright']);
        $this->db->bind(':tag', $data['tag']);
        $this->db->bind(':created_by', $data['user_id']);

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
        $this->db->query('UPDATE books SET title = :title, author = :author, edition = :edition, publication = :publication, no_of_books = :no_of_books, container = :container, description = :description, file_type = ifnull(:file_type,file_type), cover_img_type = ifnull(:cover_img_type,cover_img_type), status = :status, copyright = :copyright, updated_by = :updated_by, updated_at = NOW() 
        WHERE id = :id');

        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':edition', $data['edition']);
        $this->db->bind(':publication', $data['publication']);
        $this->db->bind(':no_of_books', $data['no_of_books']);
        $this->db->bind(':description', $data['description']);
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
        if ($data['availability'] === true) {
            $this->db->bind(':container', $data['container']);
        } else {
            $this->db->bind(':container', null);
        }
        $this->db->bind(':copyright', $data['copyright']);
        // $this->db->bind(':tag', $data['tag']);
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
            // $this->db->query('INSERT INTO books (title, author, publication, container, tmp_no, created_by, created_at) VALUES (:title, :author, :publication, :container, :tmp_no, :created_by, now()) ON DUPLICATE KEY UPDATE tmp_no=:tmp_no');
            $this->db->query('INSERT INTO books (title, author, publication, container, status, tmp_no, created_by, created_at)
            SELECT :title, :author, :publication, :container, :status, :tmp_no, :created_by, now()
            FROM DUAL
            WHERE NOT EXISTS (SELECT * FROM books WHERE tmp_no = :tmp_no)  
            LIMIT 1');

            for ($i=2; $i<=$no_of_rows; $i++) {
                // Bind values
                $this->db->bind(':title', $data[$i]['B']);
                $this->db->bind(':author', $data[$i]['C']);
                $this->db->bind(':publication', $data[$i]['D']);
                $this->db->bind(':container', $data[$i]['E']);
                $this->db->bind(':status', 1);
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
            echo "Failed: " . $e->getMessage(); // this is wrong
            // throw new $e; // this is correct but not working
        }
    }

    public function importNew($data, $no_of_rows, $table)
    {
        // die(var_dump($data[1]['C']));
        // die(var_dump($nr));
        if ($table == 'new_books') {
            try {
                // Start time
                // $time_start = microtime(true);
    
                // Start transaction
                $this->db->beginTransaction();
                // Init query
                // $this->db->query('INSERT INTO books (title, author, publication, container, tmp_no, created_by, created_at) VALUES (:title, :author, :publication, :container, :tmp_no, :created_by, now()) ON DUPLICATE KEY UPDATE tmp_no=:tmp_no');
                $this->db->query('INSERT INTO new_books (title, author, publisher, copyright, addition_no, serial_no, no_of_pages, created_at, created_by )
                SELECT :title, :author, :publisher, :copyright, :addition_no, :serial_no, :no_of_pages, now(), :created_by
                FROM DUAL
                WHERE NOT EXISTS (SELECT * FROM new_books WHERE serial_no = :serial_no)  
                LIMIT 1');
    
                for ($i=9; $i<=$no_of_rows; $i++) {
                    // Bind values
                    $this->db->bind(':title', $data[$i]['B']);
                    $this->db->bind(':author', $data[$i]['C']);
                    $this->db->bind(':publisher', $data[$i]['D']);
                    $this->db->bind(':copyright', $data[$i]['E']);
                    $this->db->bind(':addition_no', $data[$i]['F']);
                    $this->db->bind(':serial_no', $data[$i]['A']);
                    $this->db->bind(':no_of_pages', $data[$i]['G']);
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
                echo "Failed: " . $e->getMessage(); // this is wrong
                // throw new $e; // this is correct but not working
            }
        } elseif ($table == 'new_books_en') {
            try {
                // Start time
                // $time_start = microtime(true);
    
                // Start transaction
                $this->db->beginTransaction();
                // Init query
                // $this->db->query('INSERT INTO books (title, author, publication, container, tmp_no, created_by, created_at) VALUES (:title, :author, :publication, :container, :tmp_no, :created_by, now()) ON DUPLICATE KEY UPDATE tmp_no=:tmp_no');
                $this->db->query('INSERT INTO new_books_en (title, author, serial_no, no_of_pages, created_at, created_by )
                SELECT :title, :author, :serial_no, :no_of_pages, now(), :created_by
                FROM DUAL
                WHERE NOT EXISTS (SELECT * FROM new_books_en WHERE serial_no = :serial_no)  
                LIMIT 1');
    
                for ($i=10; $i<=$no_of_rows; $i++) {
                    // Bind values
                    $this->db->bind(':title', $data[$i]['B']);
                    $this->db->bind(':author', $data[$i]['C']);
                    $this->db->bind(':serial_no', $data[$i]['A']);
                    $this->db->bind(':no_of_pages', $data[$i]['D']);
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
                echo "Failed: " . $e->getMessage(); // this is wrong
                // throw new $e; // this is correct but not working
            }
        } elseif ($table == 'new_magazine') {
            try {
                // Start time
                // $time_start = microtime(true);
    
                // Start transaction
                $this->db->beginTransaction();
                // Init query
                // $this->db->query('INSERT INTO books (title, author, publication, container, tmp_no, created_by, created_at) VALUES (:title, :author, :publication, :container, :tmp_no, :created_by, now()) ON DUPLICATE KEY UPDATE tmp_no=:tmp_no');
                $this->db->query('INSERT INTO new_books (title, author, publisher, copyright, addition_no, serial_no, no_of_pages, created_at, created_by )
                SELECT :title, :author, :publisher, :copyright, :addition_no, :serial_no, :no_of_pages, now(), :created_by
                FROM DUAL
                WHERE NOT EXISTS (SELECT * FROM new_books WHERE serial_no = :serial_no)  
                LIMIT 1');
    
                for ($i=9; $i<=$no_of_rows; $i++) {
                    // Bind values
                    $this->db->bind(':title', $data[$i]['B']);
                    $this->db->bind(':author', $data[$i]['C']);
                    $this->db->bind(':publisher', $data[$i]['D']);
                    $this->db->bind(':copyright', $data[$i]['E']);
                    $this->db->bind(':addition_no', $data[$i]['F']);
                    $this->db->bind(':serial_no', $data[$i]['A']);
                    $this->db->bind(':no_of_pages', $data[$i]['G']);
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
                echo "Failed: " . $e->getMessage(); // this is wrong
                // throw new $e; // this is correct but not working
            }
        } else {
            try {
                // Start time
                // $time_start = microtime(true);
    
                // Start transaction
                $this->db->beginTransaction();
                // Init query
                // $this->db->query('INSERT INTO books (title, author, publication, container, tmp_no, created_by, created_at) VALUES (:title, :author, :publication, :container, :tmp_no, :created_by, now()) ON DUPLICATE KEY UPDATE tmp_no=:tmp_no');
                $this->db->query('INSERT INTO new_research (title, researcher_name, type_of_research, serial_no, no_of_pages, comment, created_at, created_by )
                SELECT :title, :researcher_name, :type_of_research, :serial_no, :no_of_pages, :comment, now(), :created_by
                FROM DUAL
                WHERE NOT EXISTS (SELECT * FROM new_research WHERE serial_no = :serial_no)  
                LIMIT 1');
    
                for ($i=1; $i<=$no_of_rows; $i++) {
                    // Bind values
                    $this->db->bind(':title', $data[$i]['B']);
                    $this->db->bind(':researcher_name', $data[$i]['C']);
                    $this->db->bind(':type_of_research', $data[$i]['D']);
                    $this->db->bind(':serial_no', $data[$i]['A']);
                    $this->db->bind(':no_of_pages', $data[$i]['E']);
                    $this->db->bind(':comment', $data[$i]['F']);
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
                echo "Failed: " . $e->getMessage(); // this is wrong
                // throw new $e; // this is correct but not working
            }
        }
    }
    
    public function importEnBooks($data, $no_of_rows)
    {
        // die(var_dump($data[1]['C']));
        // die(var_dump($nr));
    }

    public function importMagazine($data, $no_of_rows)
    {
        // die(var_dump($data[1]['C']));
        // die(var_dump($nr));
    }

    public function importResearch($data, $no_of_rows)
    {
        // die(var_dump($data[1]['C']));
        // die(var_dump($nr));
    }

    public function addTag($tags, $book_id)
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

            $this->db->query('INSERT INTO books_tag (book_id, tag_id) VALUES (:book_id, :tag_id)');

            // Add tag to books
            $this->db->bind(':book_id', $book_id);
            $this->db->bind(':tag_id', $last_tag_id);

            // Execute query
            $this->db->execute();
        }
    }

    public function updateTag($tags, $book_id)
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

            $this->db->query('INSERT INTO books_tag (book_id, tag_id) VALUES (:book_id, :tag_id)');

            // Add tag to books
            $this->db->bind(':book_id', $book_id);
            $this->db->bind(':tag_id', $last_tag_id);

            // Execute query
            $this->db->execute();
        }
    }

    public function getAllBooks()
    {
        // Init query
        $this->db->query('SELECT id, book_no, title, author, edition, publication, no_of_books, container, description, file_type, cover_img_type, status, copyright, tag, created_by, created_at, updated_by, updated_at
        FROM books
        LIMIT 10');

        // Execute query
        $results = $this->db->resultSet();

        return $results;
    }

    public function getBooksByKey($key)
    {
        // Init query
        $this->db->query('SELECT id, book_no, title, author, edition, publication, no_of_books, container, description, file_type, cover_img_type, status, copyright, tag, created_by, created_at, updated_by, updated_at
        FROM books
        WHERE author LIKE :key OR title LIKE :key OR book_no LIKE :key');
        

        // Bind value
        $this->db->bind(':key', "$key%");

        // Get results from db
        $resutls = $this->db->resultSet();

        return $resutls;
    }

    public function getBooksByTag($key)
    {
        
        $keys = "'". implode("', '", explode(',', $key)) . "'";
        // echo $keys;
        // $keys = preg_replace('/\s+/', '', $keys);
        $keys = preg_replace('/\s+?\'\s+?/', '\'', $keys);
        // echo $keys;
        // Init query
        $this->db->query('SELECT DISTINCT b.id, b.book_no, b.title, b.author, b.edition, b.publication, b.no_of_books, b.container, b.description, b.file_type, b.cover_img_type, b.status, b.copyright, b.created_by, b.created_at, b.updated_by, b.updated_at,
        (SELECT count(*) FROM books_tag WHERE bt.book_id = t.id) AS total_books,
        (SELECT count(*) FROM books_tag WHERE bt.tag_id = t.id AND b.status = 1) AS total_available_books
        FROM books b
        JOIN books_tag bt ON bt.book_id = b.id
        JOIN tags t ON bt.tag_id = t.id
        WHERE t.tag IN ('.$keys.')');

        // SELECT DISTINCT b.*
        // FROM books b
        // JOIN books_tag bt ON bt.book_id = b.id
        // JOIN tags t ON bt.tag_id = t.id
        // WHERE t.tag IN ('a','d','e')
        
        // Bind value
        // $this->db->bind(':key', "a");

        // Get results from db
        $resutls = $this->db->resultSet();

        return $resutls;
    }

    public function getBookById($id)
    {
        // Init query
        $this->db->query('SELECT id, book_no, title, author, edition, publication, no_of_books, container, description, file_type, cover_img_type, status, copyright, tag, created_by, created_at, updated_by, updated_at 
        FROM books 
        WHERE id = :id ');

        // Bind value
        $this->db->bind(':id', $id);

        if ($row = $this->db->single()) {
            return $row;
        }
    }

    public function getBooksById($id)
    {
        // Init query
        $this->db->query('SELECT id, book_no, title, author, edition, publication, no_of_books, container, description, file_type, cover_img_type, status, copyright, tag, created_by, created_at, updated_by, updated_at 
        FROM books 
        WHERE series_id = :id');

        // Bind value
        $this->db->bind(':id', $id);

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

    public function findBookById($id)
    {
        // Init query
        $this->db->query('SELECT books.id, books.book_no, books.title, books.author, books.edition, books.publication, books.no_of_books, books.container, books.description, books.file_type, books.cover_img_type, books.status, books.copyright, books.tag, books.created_by, books.created_at, books.updated_by, books.updated_at, users.name
        FROM books
        JOIN users ON 
        books.created_by = users.id
        WHERE books.id = :id');

        // Bind value
        $this->db->bind(':id', $id);
        
        // Get Result from db
        $data = $this->db->single();

        return $data;
    }

    public function findBookByBookNo($book_no)
    {
        // Init query
        $this->db->query('SELECT book_no FROM books WHERE book_no = :book_no');

        // Bind value
        $this->db->bind(':book_no', $book_no);

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
        $this->db->query('DELETE FROM books WHERE id = :id');

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
        $this->db->query('DELETE FROM books_tag WHERE book_id = :id');

        // Bind value
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
