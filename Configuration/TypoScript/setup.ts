
plugin.tx_twsimpleworklist_frontendsimpleworklist {
  view {
    templateRootPaths.0 = EXT:tw_simpleworklist/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_twsimpleworklist_frontendsimpleworklist.view.templateRootPath}
    partialRootPaths.0 = EXT:tw_simpleworklist/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_twsimpleworklist_frontendsimpleworklist.view.partialRootPath}
    layoutRootPaths.0 = EXT:tw_simpleworklist/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_twsimpleworklist_frontendsimpleworklist.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_twsimpleworklist_frontendsimpleworklist.persistence.storagePid}
    #recursive = 1
    classes {
      ThomasWoehlke\TwSimpleworklist\Domain\Model\UserAccount {
        mapping {
          tableName = fe_users
          columns {
            username.mapOnProperty = userEmail
            password.mapOnProperty = userPassword
            name.mapOnProperty = userFullname
          }
        }
      }
    }
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
}



plugin.tx_twsimpleworklist._CSS_DEFAULT_STYLE (
    textarea.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    input.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    .tx-tw-simpleworklist table {
        border-collapse:separate;
        border-spacing:10px;
    }

    .tx-tw-simpleworklist table th {
        font-weight:bold;
    }

    .tx-tw-simpleworklist table td {
        vertical-align:top;
    }

    .typo3-messages .message-error {
        color:red;
    }

    .typo3-messages .message-ok {
        color:green;
    }
)
