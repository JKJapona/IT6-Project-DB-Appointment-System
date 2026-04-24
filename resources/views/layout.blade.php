<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS Admin | Medical Management</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sidebar-bg: oklch(0.985 0 0);
            --sidebar-fg: oklch(0.205 0 0);
            --sidebar-accent: oklch(0.96 0 0);
            
            /* Medical Blue Branding */
            --primary-blue: #0066ff; 
            --sidebar-active: var(--primary-blue);
            --sidebar-active-fg: #ffffff;
            
            --border-color: oklch(0.922 0 0);
            --radius: 0.625rem;
            --font-muted: #64748b;
        }

        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            margin: 0; 
            display: flex; 
            min-height: 100vh; 
            background: #f8fafc;
            color: #1e293b;
        }
        
        /* --- Sidebar Styling --- */
        .sidebar { 
            width: 250px; 
            background: var(--sidebar-bg); 
            color: var(--sidebar-fg); 
            padding: 20px 14px;
            flex-shrink: 0;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px 24px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-blue);
        }

        .sidebar h2 { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: var(--font-muted); 
            margin: 24px 0 8px 12px;
            font-weight: 700;
        }

        .sidebar nav a { 
            display: flex;
            align-items: center;
            color: var(--sidebar-fg); 
            padding: 10px 14px; 
            text-decoration: none; 
            transition: all 0.15s ease;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 2px;
            gap: 12px;
        }

        .sidebar nav a i {
            font-size: 1.1rem;
            color: var(--font-muted);
        }

        .sidebar nav a:hover { 
            background: var(--sidebar-accent); 
        }

        .sidebar nav a:hover i {
            color: var(--primary-blue);
        }

        .sidebar nav a.active { 
            background: var(--sidebar-active); 
            color: var(--sidebar-active-fg); 
        }

        .sidebar nav a.active i {
            color: var(--sidebar-active-fg);
        }

        /* --- Main Content Area --- */
        .main-wrapper { flex-grow: 1; display: flex; flex-direction: column; }
        
        .navbar { 
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            padding: 12px 32px; 
            border-bottom: 1px solid var(--border-color); 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .container { padding: 32px; max-width: 1200px; margin: 0 auto; width: 100%; box-sizing: border-box; }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 12px;
            border-radius: 50px;
            background: #f1f5f9;
            border: 1px solid var(--border-color);
        }

        .avatar {
            width: 28px;
            height: 28px;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
        }

        /* --- Form Styling --- */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--sidebar-fg);
            transition: all 0.2s ease;
            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--font-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* --- Table Design --- */
        .table-container {
            background: #ffffff;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        th {
            background: oklch(0.985 0 0);
            color: var(--font-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            color: oklch(0.205 0 0);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: oklch(0.99 0 0); }

        /* --- Button Design --- */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: 1px solid var(--border-color);
            line-height: 1;
        }

        .btn-primary {
            background: var(--primary-blue);
            color: #ffffff;
            border: 1px solid var(--primary-blue);
        }

        .btn-primary:hover {
            background: #0052cc;
            border-color: #0052cc;
            color: #ffffff;
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--sidebar-fg);
        }

        .btn-secondary:hover { background: var(--sidebar-accent); }

        .btn-danger {
            background: #ffffff;
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-danger:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }

        /* --- Badges --- */
        .badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef9c3; color: #854d0e; }

        /* --- Appointment/Details Card Design --- */
.details-card {
    background: #ffffff;
    padding: 32px;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 24px;
}

.details-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 20px;
    margin-bottom: 24px;
}

.label-sm {
    color: var(--font-muted);
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    display: block;
    margin-bottom: 4px;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

.info-section {
    margin-bottom: 24px;
}

.info-title {
    color: var(--sidebar-fg);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 12px;
    border-left: 3px solid var(--primary-blue);
    padding-left: 12px;
}

.info-content {
    margin-left: 15px;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #475569;
}

.meta-box {
    background: var(--sidebar-accent);
    padding: 16px;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-size: 0.8rem;
    color: var(--font-muted);
}
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-hospital"></i>
            <span>HMS Pro</span>
        </div>

        <nav>
            <h2>Management</h2>
            <a href="{{ route('departments.index') }}" class="{{ request()->is('departments*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Departments
            </a>
            <a href="{{ route('staff.index') }}" class="{{ request()->is('staff*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Medical Staff
            </a>
            <a href="{{ route('patients.index') }}" class="{{ request()->is('patients*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Patients
            </a>
            <a href="{{ route('schedules.index') }}" class="{{ request()->is('schedules*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Schedules
            </a>
            <a href="{{ route('appointments.index') }}" class="{{ request()->is('appointments*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i> Appointments
            </a>
            
            <h2>Billing & Access</h2>
            <a href="{{ route('invoices.index') }}" class="{{ request()->is('invoices*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Invoices
            </a>
            <a href="{{ route('user_accounts.index') }}" class="{{ request()->is('user_accounts*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> User Accounts
            </a>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="navbar">
            <div style="font-weight: 600; font-size: 1rem; color: var(--font-muted);">
                {{ Str::headline(request()->segment(1) ?? 'Dashboard') }}
            </div>
        </header>

        <main class="container">
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; border: 1px solid #bbf7d0; font-size: 0.875rem;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>