<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Path to the folder for saving signed ipa and plist files

$ipa = new CURLFile(realpath('ScarletAlpha.ipa'));
$p12 = new CURLFile(realpath('cert.p12'));
$mobileprovision = new CURLFile(realpath('cert.mobileprovision'));
$password = ('AppleP12.com')
$title = ('Frozen')

    //  Sign the .ipa file with zsign
    $signedIpa = . '/signed_' . $_FILES['ipa']['name'];
    exec("zsign -k $p12 -m $mobileprovision -s \"$password\" -o signedipa $ipa");

    // Creates a .plist file
    $plist = <<<PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>items</key>
    <array>
        <dict>
            <key>assets</key>
            <array>
                <dict>
                    <key>kind</key>
                    <string>software-package</string>
                    <key>url</key>
                    <string>https://carnagesigner.com/{$signedIpa}</string>
                </dict>
            </array>
            <key>metadata</key>
            <dict>
                <key>bundle-identifier</key>
                <string>com.example.app</string>
                <key>bundle-version</key>
                <string>1.0</string>
                <key>kind</key>
                <string>software</string>
                <key>title</key>
                <string>{$title}</string>
            </dict>
        </dict>
    </array>
</dict>
</plist>
PLIST;

    $plistPath = . 'app.plist';
    file_put_contents($plistPath, $plist);

    // Plist
    $installLink = "itms-services://?action=download-manifest&url=https://carnagesigner.com/{$plistPath}"; //
    echo "This is a test bro <a href=\"$installLink\">Click the link</a><br>";

    // Saves Link To Server:
    $ipaLink = . 'link.txt';
    file_put_contents($ipaLink, $installLink);
    echo "Successfully saved to server.";
}

?>
