# Production Backup and Restore Evidence

Status: **IMPLEMENTED IN REPOSITORY / PRODUCTION GO BLOCKED**

The application uses `spatie/laravel-backup` v10. Repository scheduling is implemented, but off-host storage, encryption secret, notifications, the system cron entry and restore proof still require production infrastructure evidence.

## Implemented repository contract

- package: `spatie/laravel-backup:^10.0`;
- resolved version: 10.3.0;
- database connection: current `DB_CONNECTION`;
- included files: `storage/app/private` and `storage/app/public`;
- excluded files: backup destination and temporary directory;
- local development disk: `backup` at `storage/app/backups`;
- archive verification enabled by default;
- daily backup at **03:00 Europe/Rome**;
- cleanup at 03:30;
- health monitor at 04:00;
- overlapping executions prevented;
- command output: `storage/logs/backup.log`.

The package supports `backup:run`, `backup:clean`, `backup:list` and `backup:monitor`. The scheduled backup contains the configured files plus the database dump.

## Laravel schedule

`routes/console.php` registers:

```text
03:00 Europe/Rome  backup:run
03:30 Europe/Rome  backup:clean
04:00 Europe/Rome  backup:monitor
```

Laravel converts these timezone-aware events to the server cron expression shown by `php artisan schedule:list`.

## Required system cron

The hosting control panel or server must invoke Laravel's scheduler every minute:

```cron
* * * * * cd /absolute/path/to/current-release && php artisan schedule:run >> /dev/null 2>&1
```

The absolute path and PHP executable are hosting-specific and remain `[DA VERIFICARE]`. Adding the Laravel schedule without this system cron does not execute backups.

## Required production environment

```text
BACKUP_NAME=doomsday-clock
BACKUP_DISKS=<off-host disk, for example s3>
BACKUP_TIME=03:00
BACKUP_TIMEZONE=Europe/Rome
BACKUP_CLEANUP_TIME=03:30
BACKUP_MONITOR_TIME=04:00
BACKUP_ARCHIVE_PASSWORD=<secret>
BACKUP_NOTIFICATION_EMAIL=<real monitored address>
BACKUP_VERIFY=true
BACKUP_MAX_AGE_DAYS=1
BACKUP_MAX_STORAGE_MB=10240
```

Production must use at least one independently protected/off-host destination. The local `backup` disk is a development fallback and is not sufficient for the production GO gate.

`BACKUP_ARCHIVE_PASSWORD` belongs in the production secret source and must not be committed. The archive is configured for AES-256 when the password exists.

Spatie Laravel Backup v10 requires PHP 8.4, ZIP and Laravel 12+ and is not supported on Windows servers. The selected production host must therefore be non-Windows and expose the required database dump binary for the chosen database engine.

## Backup gate before migrations

Before any production migration or high-risk data change, record:

- backup archive identifier and timestamp;
- immutable application release identifier;
- database connection and dump success;
- included file/object coverage;
- off-host destination and encryption evidence;
- notification/monitor result;
- archive verification result;
- responsible operator.

A successful scheduled command without off-host storage or restore proof remains **BLOCKED**.

## Restore drill

Use an isolated approved environment and record:

1. selected archive and expected point in time;
2. archive decryption/access procedure;
3. database restore procedure for the production engine;
4. restored migration/schema state;
5. representative record counts and relationships;
6. uploaded/private file accessibility;
7. application boot and production-like smoke results;
8. measured RPO and RTO;
9. cleanup and access revocation.

Never restore directly over production as the first test.

## Monitoring and failure conditions

Alert on:

- `backup:run` failure;
- `backup:clean` failure;
- `backup:monitor` unhealthy result;
- latest backup older than the approved threshold;
- storage/encryption/access error;
- restore drill overdue.

Receiver, off-host disk, retention approval, RPO/RTO, cron installation and a successful restore drill remain **BLOCKED** until supplied and verified.
