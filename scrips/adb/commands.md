### Getting the name of the current activity via ADB
- `adb shell "dumpsys activity activities | grep ResumedActivity"`
### All Installed Packages
- `adb shell "pm list packages"`
- `adb shell pm list packages -s` // System apps
- `adb shell pm list packages -3` // Third party apps or user installed apps
### Uninstall Package
- `adb uninstall <<package.name>>`
### Files
- `adb push [source] [destination]`    // Copy files from your computer to your phone.
- `adb pull [device file location] [local file location]` // Copy files from your phone to your computer.

### App install
- `adb -e install path/to/app.apk`
- -d                        - directs command to the only connected USB device...
- -e                        - directs command to the only running emulator...
- -s <serial number>        ...
- -p <product name or path> ...
- The flag you decide to use has to come before the actual adb command:
- `adb devices | tail -n +2 | cut -sf 1 | xargs -IX adb -s X install -r com.myAppPackage` // Install the given app on all connected devices.

### Uninstalling app from device
- `adb uninstall com.myAppPackage`
- `adb uninstall <app .apk name>`
- `adb uninstall -k <app .apk name>` -> "Uninstall .apk withour deleting data"
- `adb shell pm uninstall com.example.MyApp`
- `adb shell pm clear [package]` // Deletes all data associated with a package.
- `adb devices | tail -n +2 | cut -sf 1 | xargs -IX adb -s X uninstall com.myAppPackage` //Uninstall the given app from all connected devices
- `adb shell pm uninstall -k --user 0 <<system.app>>` // uninstall system apps
