function Start-Program {
    # Change the console title
    $host.UI.RawUI.WindowTitle = "Creating Zip Archive of Specified Branch"

    # Get the current date and time
    $currentDateTime = Get-Date -Format "yyyyMMdd_HHmmss"

    # Prompt the user for the branch name
    $branchName = Read-Host "Enter the branch name to create a zip file from"
    if ([string]::IsNullOrWhiteSpace($branchName)) {
        Write-Host "Branch name cannot be empty." -ForegroundColor Red
        Start-Program
        return
    }

    # Define the filename
    $filename = "${branchName}_branch_$currentDateTime.zip"

    # Prompt the user for confirmation
    $confirmation = Read-Host "Do you want to create a zip file of the branch '$branchName'? (y/n)"
    if ($confirmation -ne 'y') {
        Write-Host "Operation cancelled by user." -ForegroundColor Yellow
        Start-Sleep -Seconds 3
        exit
    }

    # Create the zip file of the specified branch
    try {
        git archive -o $filename $branchName
        Write-Host "Git archive command executed successfully." -ForegroundColor Green
    } catch {
        Write-Host "Error executing git archive command: $_" -ForegroundColor Red
        Start-Program
        return
    }

    # Ensure the zip file is created
    if (Test-Path $filename) {
        Write-Host "Created zip file: $filename" -ForegroundColor Green
    } else {
        Write-Host "Failed to create zip file: $filename" -ForegroundColor Red
        Start-Program
        return
    }

    # Display a customized countdown timer before closing
    for ($i = 3; $i -ge 1; $i--) {
        switch ($i) {
            3 { $color = "Yellow" }
            2 { $color = "Cyan" }
            1 { $color = "Magenta" }
        }
        Write-Host ("This window will close in {0} seconds..." -f $i) -ForegroundColor $color
        Start-Sleep -Seconds 1
    }

    Write-Host "Closing now..." -ForegroundColor Red
    Start-Sleep -Seconds 1
}

# Start the program
Start-Program