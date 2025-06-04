# Already Media Full Stack — Тестовое задание

## 🚀 Установка

### 1. Установите WordPress
Разверните свежую копию WordPress на вашем локальном сервере или хостинге.

### 2. Установите и активируйте тему
Скопируйте папку с темой в директорию `wp-content/themes/`, затем активируйте её в админке WordPress.  
Название темы: **Already Media Full Stack**

### 3. Установите плагин Advanced Custom Fields (ACF)
Перейдите в **Плагины → Добавить новый**, найдите **Advanced Custom Fields**, установите и активируйте плагин.

---

## 📦 Загрузка тестовых данных

Для загрузки тестовых данных выполните следующий PHP-скрипт из корня вашего WordPress-проекта:

```bash
php wp-content/themes/already-media-full-stack/test-data/set-test-data.php