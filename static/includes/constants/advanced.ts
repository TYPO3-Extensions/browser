plugin.tx_browser_pi1 {
  # cat=Browser - Advanced/enable/100;          type=int+;   label= Recursion Guard: Limit of runs per loop. The Browser checks fault-prone loops by the recursion guard limit. If a loop cross this limit, the workflow will exit. You will get an error prompt in the frontend. The limit is by default 10.000 runs per one loop.
  advanced.recursionGuard = 10000
  # cat=Browser - Advanced/others/999;          type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  advanced.links = Click me!
}