plugin.tx_browser_pi1 {
  frameworks {
    foundation {
      templating {

        # cat=browser foundation - framework - navigation topbar/150/100; type=string;               label= Name:Your company name. I.e: Die Netzmacher
        components.navigation.topbar.name             = Die Netzmacher
        # cat=browser foundation - framework - navigation topbar/150/100; type=string;               label= Icon label:The icon lable is displayed in minimised menus.
        components.navigation.topbar.icon.label       = Menü
        # cat=browser foundation - framework - navigation topbar/150/200; type=options[contain-to-grid,contain-to-grid fixed,fixed];  label= top bar position: Full-browser width by default. To make the top bar stay fixed as you scroll, select "fixed". If you want your navigation to be set to your grid width, wrap it with "contain-to-grid". You may use fixed and contain-to-grid together.
        components.navigation.topbar.position         = contain-to-grid
        # cat=browser foundation - framework - navigation topbar/150/201; type=options[left,right];  label= main menu position: left or right.
        components.navigation.topbar.section.position = right

      }
    }
  }
}