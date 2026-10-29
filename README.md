# Онлайн кітапхана (PHP + MySQL)

## XAMPP-та іске қосу
1. XAMPP Control Panel -> **Apache** және **MySQL** -> Start.
2. Бұл `library` папкасын `C:\xampp\htdocs\library` ішіне көшіріңіз.
3. Браузерде http://localhost/phpmyadmin -> **Import** -> `db.sql` таңдап -> Go.
4. http://localhost/library/ ашыңыз.
5. Тіркелген **бірінші** қолданушы автоматты түрде admin болады.

## Талаптар қалай орындалған
- Кемінде 3 бет: index, books, login, register, my_loans, book_form
- Кемінде 3 кесте: users, categories, books, loans (4 кесте)
- Тіркелу және кіру: register.php, login.php (password_hash)
- CRUD (books): book_form.php (Create/Update), books.php (Read), book_delete.php (Delete)
- Іздеу/сүзу: books.php (атауы, автор, санат + беттеу)
- Валидация: клиент (HTML5 + app.js) және сервер (PHP)
- Қауіпсіздік: PDO prepared statements (SQL injection), htmlspecialchars (XSS), CSRF токен, password_hash, session_regenerate_id, иелік тексеру
- 9-апта frontend: HTML + CSS + JavaScript, адаптивті (мобильді мәзір, grid)

## GitHub (апта сайын кемінде 3 коммит)
```
git init
git add .
git commit -m "Add database schema"
git commit -m "Add auth pages"
git remote add origin <сіздің репозиторий сілтемеңіз>
git push -u origin main
```

## Апталық есеп үлгісі
- Орындалған тапсырмалар:
- Жобаның қазіргі күйі (пайызбен):
- Кездескен мәселелер және шешімдері:
- Келесі аптаға жоспар:
- GitHub коммиттеріне сілтеме:
