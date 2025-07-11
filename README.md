# LandingPage-Laragon

A beautiful, modern, and responsive landing page for Laragon local development environment. This custom dashboard replaces the default Laragon homepage with an enhanced user interface featuring live search, recent projects display, and a stunning glassmorphism design.

## ✨ Features

- **🔍 Live Search**: Real-time search functionality to quickly find your projects
- **📂 Recent Projects**: Display recently modified projects with project type detection
- **🎨 Modern Design**: Beautiful glassmorphism UI with gradient backgrounds
- **📱 Responsive**: Works perfectly on desktop, tablet, and mobile devices
- **⚡ Fast Performance**: Lightweight and optimized for speed
- **🔧 Project Type Detection**: Automatically detects PHP, Node.js, HTML, and other project types
- **🌟 Animations**: Smooth hover effects and loading animations
- **💾 Server Information**: Display PHP version, document root, and server details

## 🛠️ Installation

1. **Replace the default Laragon index file**:
   ```bash
   # Navigate to your Laragon www directory
   cd /d/laragon/www
   
   # Backup the original index file (optional)
   mv index.php index.php.backup
   
   # Clone or download this repository
   git clone https://github.com/Lyramor/LandingPage-Laragon.git
   
   # Copy the new index.php to your www directory
   cp LandingPage-Laragon/index.php .
   ```

2. **Or manually download**:
   - Download the `index.php` file from this repository
   - Replace the existing `index.php` in your `laragon/www` directory

## 🚀 Usage

1. Start your Laragon server
2. Open your browser and navigate to `http://localhost`
3. Enjoy the new beautiful dashboard!

### Search Functionality
- Type in the search box to find projects in real-time
- Click on search results to navigate directly to your project
- Press Enter or click "Search Now" to navigate to a project

### Recent Projects
- Automatically shows your 6 most recently modified projects
- Click on any project to open it in your browser
- Shows project type, path, and last modified time

## 🎨 Design Features

- **Glassmorphism Effect**: Modern glass-like design with blur effects
- **Gradient Background**: Beautiful blue gradient with animated floating shapes
- **Typography**: Clean Inter font family for better readability
- **Color Scheme**: Professional blue color palette with white accents
- **Animations**: Smooth transitions and hover effects
- **Grid Pattern**: Subtle background grid for added visual depth

## 📋 Project Type Detection

The dashboard automatically detects and displays project types based on files present:

- **PHP Project**: Contains `index.php`
- **HTML Project**: Contains `index.html`
- **Node.js Project**: Contains `package.json`
- **PHP/Composer Project**: Contains `composer.json`
- **JavaScript App**: Contains `app.js`
- **Directory**: Default for other folders

## 🔧 Technical Details

- **PHP Version**: Compatible with PHP 7.4+
- **Dependencies**: None (uses only vanilla PHP, HTML, CSS, and JavaScript)
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)
- **Responsive**: Mobile-first design approach

## 📁 File Structure

```
LandingPage-Laragon/
├── index.php          # Main dashboard file
├── README.md          # This file
```

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 💡 Feature Requests

Have an idea for a new feature? Open an issue and describe:
- What you'd like to see
- Why it would be useful
- How it might work

## 🐛 Bug Reports

Found a bug? Please open an issue with:
- Steps to reproduce
- Expected behavior
- Actual behavior
- Your environment (PHP version, browser, etc.)

## 📜 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laragon** - For the amazing local development environment
- **Inter Font** - For the beautiful typography
- **Font Awesome** - For the icons
- **CSS Glassmorphism** - For the modern design inspiration

## 📞 Support

If you find this project helpful, please consider:
- ⭐ Starring the repository
- 🐛 Reporting bugs
- 💡 Suggesting new features
- 🤝 Contributing to the code

## 📚 Related Projects

- [Laragon Official](https://laragon.org/) - The original Laragon project
- [Laragon Documentation](https://laragon.org/docs/) - Official documentation

---

**Made with ❤️ by [Lyramor](https://github.com/Lyramor)**

*Transform your Laragon experience with this beautiful, modern dashboard!*
