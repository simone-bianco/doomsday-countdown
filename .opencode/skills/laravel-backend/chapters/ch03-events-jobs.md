# Events & Jobs

## Load When
adding async work, broadcasts, or queues.

Queued controllers dispatch and return `202 Accepted`. Jobs use `ShouldQueue` and `SerializesModels`; broadcast success/failure. Do not perform expensive work synchronously inside controllers.
