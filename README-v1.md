# **Library Management API**

## **Overview**
The Library Management API is designed to manage a collection of books and their associated categories. It supports user roles, authentication, and authorization to ensure secure access and operations. The API allows readers to browse and search the library, while administrators can perform CRUD operations on books, categories, and user data.

---

## **Key Features**

### **1. Authentication and Authorization**
- **Authentication**:
    - Powered by Laravel Sanctum, ensuring secure access to the API.
    - Only authenticated users can access the API.

- **Authorization**:
    - **Admin Role**:
        - Full access to perform CRUD operations on books, categories, and users.
    - **Reader Role**:
        - Limited to read-only access (e.g., fetching and searching books and categories).

---

### **2. Models and Relationships**

#### **Books**
- Represents a library book.
- **Attributes**:
    - `name`: The title of the book.
    - `owner`: The author of the book.
    - `about`: A brief description of the book.
    - `category_id`: Foreign key referencing the `categories` table.
- **Relationships**:
    - A book belongs to a category (`category`).

#### **Categories**
- Represents a book category.
- **Attributes**:
    - `name`: The name of the category.
- **Relationships**:
    - A category can have multiple books (`books`).

---

### **3. Features by Endpoint**

#### **Books**
- **`GET /api/v1/books`**:
    - Retrieves a paginated list of books, including their categories.
- **`GET /api/v1/books/{id}`**:
    - Retrieves the details of a specific book.
- **`POST /api/v1/books`** (Admin only):
    - Adds a new book to the library.
- **`PUT /api/v1/books/{id}`** (Admin only):
    - Updates the details of an existing book.
- **`DELETE /api/v1/books/{id}`** (Admin only):
    - Deletes a book from the library.
- **`GET /api/v1/books/search?q={query}`**:
    - Searches for books by name, author, or category name.

#### **Categories**
- **`GET /api/v1/categories`**:
    - Retrieves all categories with their associated books.
- **`GET /api/v1/categories/{id}`**:
    - Retrieves a specific category and its books.
- **`POST /api/v1/categories`** (Admin only):
    - Adds a new category.
- **`PUT /api/v1/categories/{id}`** (Admin only):
    - Updates an existing category.
- **`DELETE /api/v1/categories/{id}`** (Admin only):
    - Deletes a category.
- **`GET /api/v1/categories/search?q={query}`**:
    - Searches for categories by name or book name.

---

### **4. User Roles**
- **Admin**:
    - Can manage books, categories, and user data.
- **Reader**:
    - Can view and search books and categories.

---

### **5. API Responses**
The API uses a consistent JSON response format:
- **Success Response**:
  ```json
  {
      "success": true,
      "message": "Books retrieved successfully",
      "data": { /* Data Object */ },
      "status": 200
  }
