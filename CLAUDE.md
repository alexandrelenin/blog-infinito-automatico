# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **BIA - Blog Infinito Automático**, a WordPress plugin for automated blog content generation. The plugin allows users to generate ideas, produce content, and automatically publish to WordPress blogs using AI services.

## Architecture

### Core Structure
- **Main Plugin File**: `bia.php` - Entry point with plugin metadata and includes
- **Admin Dashboard**: `admin/dashboard.php` - Creates WordPress admin menu and tab navigation
- **Tab Modules**: `admin/tabs/` - Individual functionality modules for each admin tab
- **Assets**: `assets/css/` and `assets/js/` - Frontend styles and scripts
- **Core Includes**: `includes/` - License verification and settings registration

### Key Components

#### Tab-Based Architecture
The plugin uses a tab-based interface with the following modules:
- `minha-conta.php` - Account settings and API key configuration
- `gerar-ideias.php` - Content idea generation
- `produzir-conteudos.php` - Content production with layout and functionality separation
- `agendamento-rascunhos.php` - Post scheduling functionality
- `calendario.php` - Calendar view for scheduled posts
- `historico.php` - Content history tracking
- `excluidos.php` - Deleted content management
- `loja-da-bia.php` - Plugin store/marketplace
- `seja-afiliado.php` - Affiliate program management

#### License System
- `includes/bia-licenca-verificacao.php` - Remote license verification against `bloginfinitoautomatico.com.br`
- Plugin blocks functionality if email validation fails
- Uses `BIA_PLUGIN_BLOQUEADO` constant to control access

#### Asset Management
- Dynamic CSS/JS loading based on active tab
- FullCalendar integration for scheduling features
- Custom styling for each major interface

## Development Notes

### WordPress Integration
- Uses WordPress hooks: `admin_menu`, `admin_init`, `admin_enqueue_scripts`
- Follows WordPress plugin standards for activation/deactivation
- Integrates with WordPress options API for settings storage

### External Dependencies
- OpenAI/GPT-4 API integration for content generation
- DALL·E integration for image generation
- FullCalendar library for scheduling interface
- Remote license validation service

### Security Considerations
- Input sanitization using `sanitize_text_field()`
- Email validation with WordPress `is_email()`
- Remote API calls with proper error handling and timeouts

### File Organization Pattern
Content production features are split into:
- Layout file (`produzir-conteudos-layout.php`) - UI rendering
- Functionality file (`produzir-conteudos-funcionalidades.php`) - Backend logic
- Main tab file - Includes and orchestration

## Common Development Tasks

### Adding New Features
1. Create new tab file in `admin/tabs/`
2. Add tab navigation in `admin/dashboard.php`
3. Include CSS/JS files if needed via enqueue functions
4. Add corresponding case in dashboard switch statement

### Development Environment
- **Docker Setup**: Use provided `docker-compose.yml` for local testing
- **WordPress Version**: 6.8.2+ (tested)
- **PHP Version**: 7.4+ to 8.x compatible
- **Testing URL**: http://localhost:8080

### Debugging
- WordPress debug logs for plugin loading issues  
- License verification errors are logged during API calls
- Use Docker environment for safe testing

### Recent Fixes (v1.2.1)
- **Fatal Error**: Fixed includes order (tabs before dashboard)
- **Calendar**: Replaced `cal_days_in_month()` with native `date()`
- **PHP 8.x**: Replaced `match()` with `switch/case`
- **Navigation**: Fixed forced redirection logic
- **Compatibility**: Removed external PHP extension dependencies

### API Integration
- OpenAI API key stored as `bia_gpt_dalle_key` option
- User data stored in WordPress options: `bia_nome_completo`, `bia_email_compra`, etc.
- Remote verification endpoint: `https://bloginfinitoautomatico.com.br/wp-json/bia/v1/verificar-email`